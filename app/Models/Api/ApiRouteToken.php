<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRouteToken extends Model
{
    protected $table = 'api_route_token';

    public $timestamps = false;

    public function token(): BelongsTo
    {
        return $this->belongsTo(ApiToken::class);
    }
}
