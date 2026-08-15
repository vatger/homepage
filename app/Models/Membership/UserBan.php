<?php

namespace App\Models\Membership;

use Carbon\Carbon;
use Database\Factories\UserBanFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBan extends Model
{
    use HasFactory;

    protected $table = 'user_bans';

    protected static function newFactory(): Factory
    {
        return UserBanFactory::new();
    }

    protected $appends = ['permanent'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'type' => UserBanType::class,
    ];

    protected $fillable = [
        'user_id',
        'author_id',
        'type',
        'starts_at',
        'ends_at',
        'homepage',
        'forum',
        'teamspeak',
        'reason',
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
