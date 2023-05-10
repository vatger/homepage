<?php

namespace App\OpenApi\Models;

use App\Models\Membership\User\User;
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
}
