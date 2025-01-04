<?php

namespace App\Http\Middleware\Membership;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckHomepageBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_currently_homepage_banned) {
                // Account is locally or globally banned
                return redirect()->route('member.banned');
            }
        }

        return $next($request);
    }
}
