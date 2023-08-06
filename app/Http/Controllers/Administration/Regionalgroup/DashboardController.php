<?php

namespace App\Http\Controllers\Administration\Regionalgroup;

use App\Models\Membership\User\User;
use App\Models\Regionalgroup_remove\Regionalgroup;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends RegionalgroupController
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Regionalgroup::class);

        $regionalgroups = Regionalgroup::all();
        return $this->prepareView('administration.regionalgroup.index')->with('regionalgroups', $regionalgroups);
    }

    public function view(Request $request, Regionalgroup $regionalgroup): Redirect|View
    {
        $this->authorize('view', $regionalgroup);

        $regionalgroup->loadMissing('accounts', 'fir', 'chief', 'deputy', 'mentors', 'eventler', 'navigators', 'requests', 'templates');

        $members = $regionalgroup->members;
        $membersPaginator = new LengthAwarePaginator($members, $members->count(), 15);

        return view('administration.regionalgroup.regionalgroup')->with(['regionalgroup' => $regionalgroup, 'members' => $membersPaginator]);
    }
}
