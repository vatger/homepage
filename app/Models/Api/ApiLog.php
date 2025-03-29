<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    protected $table = 'api_logs';

    protected $fillable = ['token_id', 'time', 'endpoint', 'ip_address'];

    public function token(): BelongsTo
    {
        return $this->belongsTo(ApiToken::class, 'token_id', 'id');
    }
}
