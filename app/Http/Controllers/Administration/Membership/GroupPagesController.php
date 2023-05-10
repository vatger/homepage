<?php

namespace App\Http\Controllers\Administration\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): View
    {
        $this->authorize('membership-access');
        $groups = Role::query()->paginate('25');

        return $this->prepareView('administration.membership.groups.index')->with('groups', $groups);
    }

    public function createRole(Request $request)
    {
        // TODO
    }

    public function removeRole(Request $request)
    {
        // TODO Proper Authorization
        $this->authorize('membership-access');

        try {
            $role = \Spatie\Permission\Models\Role::query()->findOrFail($request->route('role'));
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withErrors(['Die Gruppe konnte nicht gefunden werden']);
        }

        $role->delete();

        return redirect()
            ->route('administration.membership.groups')
            ->with(['success' => 'Die Gruppe wurde erfolgreich gelöscht']);
    }

    /**
     * Gets the roles and paginates these (ajax)
     *
     * @param Request $request
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getGroupsPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('membership-access'); //TODO???

        return Role::query()->paginate(15);
    }

    /**
     * Searches the roles by the given parameters (ajax)
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getGroupsSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('membership-access'); //TODO???

        return Role::query()
            ->where('name', 'LIKE', '%' . $request->get('search_param') . '%')
            ->get();
    }
}
