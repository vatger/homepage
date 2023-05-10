<?php

namespace App\Http\Controllers\Administration\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\Role;
use App\Models\Membership\User\User;
use App\Models\TeamSpeak\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): View
    {
        $this->authorize('membership-access');
        return view('administration.membership.membership.index');
    }

    /**
     * Gets the users and paginates these paginated (ajax)
     *
     * @param Request $request
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUsersPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('membership-access');

        return User::query()
            ->orderBy('id')
            ->with(['userData', 'regionalgroups:id,name,created_at'])
            ->paginate(15);
    }

    /**
     * Searches the user by the given parameters (ajax)
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUsersSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('membership-access');

        $namearray = explode(' ', $request->get('search_param'), 2);

        if (count($namearray) > 1) {
            return User::query()
                ->with('userData:account_id,subdivision_code')
                ->where('firstname', 'LIKE', '%' . $namearray[0] . '%')
                ->where('lastname', 'LIKE', '%' . ($namearray[1] ?? $namearray[0]) . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orWhere('id', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            return User::query()
                ->with('userData:account_id,subdivision_code')
                ->where('id', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orWhere('firstname', 'LIKE', '%' . $namearray[0] . '%')
                ->orWhere('lastname', 'LIKE', '%' . ($namearray[1] ?? $namearray[0]) . '%')
                ->orderBy('id', 'ASC')
                ->get();
        }
    }

    /**
     * Returns the teamspeak registration data for a certain id
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUserTeamspeakDetails(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('membership-access');

        return Registration::query()->find($request->get('tsid'));
    }
}
