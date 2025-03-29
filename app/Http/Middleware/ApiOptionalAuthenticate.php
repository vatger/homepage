<?php

namespace App\Http\Middleware;

use App\Models\Api\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOptionalAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization', '');
        $token = str_replace('Token', '', $header);
        $token = str_replace('Bearer', '', $token);
        $token = ltrim($token);
        if (ApiToken::tokenExists($token)) {
            try {
                $api_token = ApiToken::tokenFind($token);
                Auth::guard('api')->login($api_token);

                return $next($request);
            } catch (\Throwable $t) {
                return response(['message' => 'Failed to log in token'], 500);
            }
        }

        return $next($request);
    }
}
