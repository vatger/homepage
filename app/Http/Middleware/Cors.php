<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class Cors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ];

        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)->withHeaders($headers);
        }

        // Handle normal requests including HEAD
        $response = $next($request);

        // Ensure headers are set even for HEAD requests
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
