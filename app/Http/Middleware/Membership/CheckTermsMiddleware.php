<?php

namespace App\Http\Middleware\Membership;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTermsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (! $user->settings->agreed) {
                // Account is locally or globally banned
                return redirect()->route('check-terms');
            }
        }

        return $next($request);
    }
}
