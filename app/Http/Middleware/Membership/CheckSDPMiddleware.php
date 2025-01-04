<?php

namespace App\Http\Middleware\Membership;

use App\Models\Membership\UserStaffDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSDPMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->can('administration.access') || $user->teams()->count() > 0) {
                if (! $user->staffDetails) {
                    $sd = new UserStaffDetail;
                    $sd->user_id = $user->id;
                    $sd->joined_staff_at = now();
                    $sd->staff_email = strtolower(substr($user->firstname, 0, 1).'.'.$user->lastname.'@vatger.de');
                    $sd->save();
                } else {
                    if ($user->staffDetails->leaving_staff_at) {
                        $user->staffDetails->leaving_staff_at = null;
                        $user->staffDetails->save();
                    }
                }
                $user = $user->fresh();
            }

            if ($user?->staffDetails) {
                if (! $user?->staffDetails?->accepted_data_protection_at) {
                    return redirect()->route('administration.sdp');
                }
            }
        }

        return $next($request);
    }
}
