<?php

namespace App\Models\Regionalgroup;

use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Regionalgroup extends Model
{
    protected $table = 'regionalgroups_regionalgroups';

    protected $fillable = [
        'id',
        'name',
        'fir_id',
        'vacc_nbr',
        'email',
        'chief_id',
        'deputy_id',
        'staff_group_id',
        'voting_group_id',
        'mentor_group_id',
        'navler_group_id',
        'eventler_group_id',
        'member_group_id',
        'guest_group_id',
    ];

    protected $hidden = [
        'staff_group_id',
        'voting_group_id',
        'mentor_group_id',
        'navler_group_id',
        'eventler_group_id',
        'member_group_id',
        'guest_group_id',
    ];

    protected $appends = [
        //'members',
        'membersCount',
        //'guests',
        'guestsCount',
        //'controllers',
        //'pilots',
    ];

    /**
     * The FIR this regionalgroup belongs to
     */
    public function fir(): BelongsTo
    {
        return $this->belongsTo(FlightInformationRegion::class, 'fir_id', 'id');
    }

    /**
     * All associated accounts
     * Regardless of guest status
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_account_regionalgroup', 'regionalgroup_id', 'user_id')
            ->withPivot('pilot', 'controller', 'guest')
            ->withTimestamps();
    }

    /**
     * All fullmember accounts
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_account_regionalgroup', 'regionalgroup_id', 'user_id')
            ->withPivot('pilot', 'controller', 'guest')
            ->wherePivot('guest', '=', 0)
            ->withTimestamps();
    }

    /**
     * All guestmember accounts
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_account_regionalgroup', 'regionalgroup_id', 'user_id')
            ->withPivot('pilot', 'controller', 'guest')
            ->wherePivot('guest', '=', 1)
            ->withTimestamps();
    }

    /**
     * Only full regionalgroup members
     *
     * @return [type] [description]
     */
    public function getMembersAttribute()
    {
        return $this->accounts->reject(function ($acc) {
            return $acc->pivot->guest;
        });
    }

    public function getMembersCountAttribute(): int
    {
        return count($this->getMembersAttribute());
    }

    /**
     * Only guest members of the regionalgroup
     *
     * @return [type] [description]
     */
    public function getGuestsAttribute()
    {
        return $this->accounts->filter(function ($acc) {
            return $acc->pivot->guest;
        });
    }

    public function getGuestsCountAttribute(): int
    {
        return count($this->getGuestsAttribute());
    }

    /**
     * The current regionalgroup chief
     */
    public function chief(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chief_id', 'id');
    }

    /**
     * The deputy of the regionalgroup
     */
    public function deputy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deputy_id', 'id');
    }

    /**
     * All atc/atd mentors of the regionalgroup
     */
    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_mentors', 'regionalgroup_id', 'user_id')
            ->withPivot('chief', 'senior')
            ->orderByDesc('regionalgroups_mentors.chief')
            ->orderByDesc('regionalgroups_mentors.senior');
    }

    /**
     * All members that are participating in the navigation team of the regionalgroup
     */
    public function navigators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_navigators', 'regionalgroup_id', 'user_id')
            ->withPivot('chief', 'deputy')
            ->orderByDesc('regionalgroups_navigators.chief')
            ->orderByDesc('regionalgroups_navigators.deputy');
    }

    /**
     * The event team of the regionalgroup
     */
    public function eventler(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'regionalgroups_eventler', 'regionalgroup_id', 'user_id')
            ->withPivot('chief', 'deputy')
            ->orderByDesc('regionalgroups_eventler.chief')
            ->orderByDesc('regionalgroups_eventler.deputy');
    }

    /**
     * All requests that have been stated to the regionalgroup
     */
    public function requests(): HasMany
    {
        return $this->hasMany(RegionalgroupRequest::class, 'regionalgroup_id', 'id');
    }

    /**
     * All aerodromes that are assigned to this regionalgroup
     */
    public function aerodromes(): BelongsToMany
    {
        return $this->belongsToMany(Aerodrome::class, 'navigation_aerodrome_regionalgroup', 'regionalgroup_id', 'aerodrome_id');
    }

    /**
     * All templates that are assigned to this regionalgroup
     */
    public function templates(): HasMany
    {
        return $this->hasMany(RegionalgroupTemplate::class, 'regionalgroup_id', 'id');
    }

    /**
     * TODO: doc
     */
    public function membershipRequirements(): HasOne
    {
        return $this->hasOne(RegionalgroupMembershipRequirement::class, 'regionalgroup_id', 'id');
    }
}
