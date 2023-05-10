<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Regionalgroup\Regionalgroup;
use App\Models\Regionalgroup\RegionalgroupAccount;
use App\Models\Regionalgroup\RegionalgroupRequest;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasRegionalgroupConcern
{
    /**
     * All regionalgroups an account is assigned to in any way
     *
     * @return BelongsToMany [type] [description]
     */
    public function regionalgroups(): BelongsToMany
    {
        return $this->belongsToMany(Regionalgroup::class, 'regionalgroups_account_regionalgroup', 'user_id', 'regionalgroup_id')
            ->withPivot('pilot', 'controller', 'guest')
            ->with('fir')
            ->withTimestamps();
    }

    /**
     * Gets the home regionalgroup of the member
     *
     */
    public function getHomeRegionalgroup()
    {
        return $this->regionalgroups()
            ->where('guest', 0)
            ->first();
    }

    /**
     * All requests to regionalgroups from this account
     *
     * @return HasMany [type] [description]
     */
    public function regionalgroupRequests(): HasMany
    {
        return $this->hasMany(RegionalgroupRequest::class, 'user_id', 'id');
    }

    /**
     * Has this user created a request for the regionalgroup?
     *
     * @param Regionalgroup $regionalgroup
     * @return bool
     */
    public function hasRegionalgroupRequest(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->requests->where('user_id', $this->id)->first() != null;
    }

    /**
     * Fullmember of regionalgroup?
     *
     * @param  Regionalgroup $regionalgroup [description]
     * @return boolean                      [description]
     */
    public function isMemberOfRegionalgroup(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->members->contains($this);
    }

    /**
     * Assigned as guest?
     *
     * @param  Regionalgroup $regionalgroup [description]
     * @return boolean                      [description]
     */
    public function isGuestOfRegionalgroup(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->guests->contains($this);
    }

    /**
     * Is the account assigend as a mentor to the given regionalgroup
     *
     * @param  Regionalgroup $regionalgroup [description]
     * @return boolean                      [description]
     */
    public function isMentorOfRegionalgroup(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->mentors->contains($this);
    }

    /**
     * Is the account assigned as a navigator to the regionalgroup
     * @param  Regionalgroup $regionalgroup [description]
     * @return boolean                      [description]
     */
    public function isNavigatorOfRegionalgroup(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->navigators->contains($this);
    }

    /**
     * Does the account belong to the event team of a given regionalgroup
     *
     * @param  Regionalgroup $regionalgroup [description]
     * @return boolean                      [description]
     */
    public function isEventlerOfRegionalgroup(Regionalgroup $regionalgroup): bool
    {
        return $regionalgroup->eventler->contains($this);
    }

    /**
     * Is the account linked to any regionalgroup with the flag set to fullmembership
     *
     * @return boolean
     */
    public function isMemberOfAnyRegionalgroup(): bool
    {
        return $this->regionalgroups()
            ->wherePivot('guest', false)
            ->exists();
    }
}
