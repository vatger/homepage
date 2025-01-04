<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CookieChecker
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $has_cookie_consent = self::has_cookie_consent($request);
        if (! $has_cookie_consent) {
            return redirect()
                ->route('landing')
                ->withErrors('Accept cookies first');
        }

        return $next($request);
    }

    public static function has_cookie_consent(Request $request): bool
    {
        return collect($request->cookies)->keys()->contains(fn ($key) => $key == config('cookie-consent.cookie_key'));
    }

    public static function get_cookie_consent(Request $request): ?string
    {
        return collect($request->cookies);
    }
}
