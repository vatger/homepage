<?php

namespace App\Models\Membership\Concerns;

use App\Models\Membership\UserBan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QBuilder;

trait HasBanConcern
{
    public function initializeAppendAttributeTrait(): void
    {
        $this->append(['is_currently_homepage_banned', 'is_currently_forum_banned', 'is_currently_ts_banned']);
    }

    public function bans(): HasMany
    {
        return $this->hasMany(UserBan::class, 'user_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function getIsCurrentlyBannedAttribute(): bool
    {
        $now = Carbon::now()->utc();
        return $this->bans()
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->exists();
    }

    public function getIsCurrentlyForumBannedAttribute(): bool
    {
        $now = Carbon::now()->utc();
        return $this->bans()
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->where('forum', 1)
            ->exists();
    }

    public function getIsCurrentlyTSBannedAttribute(): bool
    {
        $now = Carbon::now()->utc();
        return $this->bans()
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->where('teamspeak', 1)
            ->exists();
    }

    public function getIsCurrentlyHomepageBannedAttribute(): bool
    {
        $now = Carbon::now()->utc();
        return $this->bans()
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->where('homepage', 1)
            ->exists();
    }

    public function getCurrentBanAttribute(): ?UserBan
    {
        $now = Carbon::now()->utc();
        return $this->bans()
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();
    }
}
