<?php

namespace App\Http\Controllers\Administration\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('viewAny', User::class);
        $user->loadMissing('userData', 'teamspeakRegistrations', 'controllerFeedback.station', 'controllerReports.station');

        return view('administration.membership.membership.user')->with('user', $user);
    }
}
