<?php

namespace App\Http\Middleware\Membership;

use App\Models\Membership\GdprRemoval;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckGDPRMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->exists()) {
                // Account is locally or globally banned
                return redirect()->route('member.removal-pending');
            }
        }
        return $next($request);
    }
}
