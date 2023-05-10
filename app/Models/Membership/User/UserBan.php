<?php

namespace App\Models\Membership\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBan extends Model
{
    protected $table = 'user_bans';

    protected $appends = ['permanent'];

    protected $dates = ['starts_at', 'ends_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function getPermanentAttribute(): bool
    {
        return is_null($this->ends_at);
    }
}
