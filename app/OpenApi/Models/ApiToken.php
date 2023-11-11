<?php

namespace App\OpenApi\Models;

use App\Models\Membership\User\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiToken extends Authenticatable
{
    protected $table = 'api_tokens';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vatsim_id', 'id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApiLog::class, 'token_id', 'id');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(ApiRouteToken::class, 'token_id', 'id');
    }

    public static function tokenFind(string $token): self
    {
        return self::query()
            ->valid()
            ->where('token', $token)
            ->firstOrFail();
    }

    public static function tokenExists(string|null $token): bool
    {
        if (is_null($token)) {
            return false;
        }
        return self::query()
            ->valid()
            ->where('token', $token)
            ->exists();
    }

    public function scopeValid($query)
    {
        return $query->where('valid_till', '>', Carbon::now());
    }

    public function check_allowed(string $route_id): bool
    {
        return Cache::remember(
            'apitoken.check_allowed.' . $this->id . '.' . $route_id,
            60,
            fn() => $this->routes()
                ->where('route_id', 'LIKE', $route_id)
                ->exists(),
            60,
        );
    }
}
