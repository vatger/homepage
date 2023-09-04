<?php

namespace App\Http\Middleware;

use App\Libraries\MembershipLibrary;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     * @throws AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        // if the user is authenticated
        if (\Auth::guard('web')->check()) {
            MembershipLibrary::seen(\Auth::guard('web')->user());
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(\Illuminate\Http\Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('vatsim.authentication.connect.login');
        }
        abort(401);
    }
}
