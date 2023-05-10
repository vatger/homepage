<?php

namespace App\OpenApi\Middleware;

use App\OpenApi\Models\ApiLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $log = [
            'token_id' => Auth::guard('api')->check() ? Auth::guard('api')->user()->id : null,
            'time' => Carbon::now(),
            'endpoint' => $request->path(),
            'ip_address' => $request->ip(),
        ];
        ApiLog::query()->create($log);
        return $next($request);
    }
}
