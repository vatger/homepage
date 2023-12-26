<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\MembershipLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BannedController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect(route('landing'));
        }
        $cache_key = 'BannedController.ban_checked.' . Auth::user()->id;
        if (!Cache::has($cache_key)) {
            MembershipLibrary::update(Auth::user(), cache: false, api_refresh: true);
            Cache::put($cache_key, true, 120);
        }
        if (Auth::user()->current_ban == null) {
            return redirect(route('landing'));
        }
        return view('pages.banned')->with('ban', Auth::user()?->current_ban);
    }
}
