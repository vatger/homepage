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
        'joined_staff_at' => 'datetime',
        'accepted_data_protection_at' => 'datetime',
        'leaving_staff_at' => 'datetime',
        'staff_email_created' => 'boolean',
        'delete_email_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
