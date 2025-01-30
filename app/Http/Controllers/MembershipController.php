<?php

namespace App\Http\Controllers;

use App\Libraries\GDPRLibrary;
use App\Libraries\MembershipLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MembershipController extends Controller
{
    public function banned(Request $request)
    {
        if (! Auth::check()) {
            return redirect(route('landing'));
        }
        $cache_key = 'MembershipController.ban_checked.'.Auth::user()->id;
        if (! Cache::has($cache_key)) {
            MembershipLibrary::update(Auth::user());
            Cache::put($cache_key, true, 120);
        }
        if (Auth::user()->current_ban == null) {
            return redirect(route('member.profile'));
        }

        return view('pages.banned')->with('ban', Auth::user()->current_ban);
    }

    public function pending_removal(Request $request)
    {
        if (! Auth::check()) {
            return redirect(route('landing'));
        }
        $user = Auth::user();
        if (! $user->isCurrentlyInRemoval()) {
            return redirect(route('landing'));
        }

        return view('pages.pending-removal');
    }

    public function pending_removal_cancel(Request $request)
    {
        if (! Auth::check()) {
            return redirect(route('landing'));
        }
        $user = Auth::user();
        $success = GDPRLibrary::cancel_deletion($user);
        if (! $success) {
            edirect(route('member.profile'))->with('error', 'GDPR removal locked. Contact support.');
        }

        return redirect(route('member.profile'))->with('success', 'GDPR removal cancelled.');

    }

    public function refresh(Request $request)
    {
        MembershipLibrary::update(Auth::user());
        sleep(10);

        return redirect()->back(fallback: route('member.profile'));
    }
}
