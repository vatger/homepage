<?php

namespace App\Http\Middleware\Membership;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckSDPMiddleware
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->load_missing('staff_details');
            dd($user);
            if ($user->staff_details) {
                if (!$user->staff_details->accepted_data_protection_at) {
                    return redirect()->route('administration.sdp');
                }
            }
        }
        return $next($request);
    }
}
