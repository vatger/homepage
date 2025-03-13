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
            $lw = ($request->route()->uri() == 'livewire/update');
            if (! $lw && ! $user->settings->agreed) {
                // Account has not agreed to all necessary polices
                return redirect()->route('check-terms', ['url' => urlencode($request->url())]);
            }
        }

        return $next($request);
    }
}
