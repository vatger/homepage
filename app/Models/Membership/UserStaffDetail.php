<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStaffDetail extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_staff_details';

    public $timestamps = false;

    protected $casts = [
        'staff_name_format' => StaffNameFormat::class,
        'joined_staff_at' => 'datetime',
        'accepted_data_protection_at' => 'datetime',
        'leaving_staff_at' => 'datetime',
        'staff_email_created' => 'boolean',
        'delete_email_at' => 'datetime',
    ];

    public function getDisplayNameAttribute(): ?string
    {
        $user = $this->relationLoaded('user')
            ? $this->getRelation('user')
            : $this->user()->first();

        if (! $user) {
            return null;
        }

        return match ($this->staff_name_format ?? StaffNameFormat::FullName) {
            StaffNameFormat::Initials => strtoupper(
                mb_substr($user->firstname, 0, 1).mb_substr($user->lastname, 0, 1),
            ),
            StaffNameFormat::FirstNameAndLastInitial => $user->firstname.' '.mb_substr($user->lastname, 0, 1).'.',
            StaffNameFormat::FullName => $user->username,
        };
    }

    public function getDisplayEmailAttribute(): ?string
    {
        return $this->staff_email
            ? str_replace('@', ' [AT] ', $this->staff_email)
            : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
