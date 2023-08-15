<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVatgerDetail extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_vatger_details';

    protected $fillable = [
        'last_seen_at',
        'registered_at',
        'active_member_at',
        'vatger_member_at',
        'active_vatger_member_at',
        'warning_inactive_at',
        'inactive_at',
        'warning_delete_at',
        'delete_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'last_seen_at' => 'datetime',
        'registered_at' => 'datetime',
        'active_member_at' => 'datetime',
        'vatger_member_at' => 'datetime',
        'active_vatger_member_at' => 'datetime',
        'warning_inactive_at' => 'datetime',
        'inactive_at' => 'datetime',
        'warning_delete_at' => 'datetime',
        'delete_at' => 'datetime',
        //'deleted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
