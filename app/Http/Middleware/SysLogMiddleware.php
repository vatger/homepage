<?php

namespace App\Http\Middleware;

use App\Models\Tech\SysLog;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SysLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! config('system.accesslog.enabled')) {
            return $next($request);
        }

        $log = [
            'account_id' => Auth::check() ? Auth::user()->id : null,
            'path' => $request->getPathInfo(),
            'method' => $request->getMethod(),
        ];

        SysLog::Log($log);

        return $next($request);
    }
}
