<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBan extends Model
{
    protected $table = 'user_bans';

    protected $appends = ['permanent'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'type' => UserBanType::class,
    ];

    protected $fillable = [
        'user_id',
        'author_id',
        'ends_at',
        'homepage',
        'forum',
        'teamspeak',
        'reason'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function endBanNow(): void
    {
        $this->ends_at = Carbon::now();
        $this->save();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->permanent || $this->ends_at >= Carbon::now();
    }

    public function getPermanentAttribute(): bool
    {
        return is_null($this->ends_at);
    }
}
