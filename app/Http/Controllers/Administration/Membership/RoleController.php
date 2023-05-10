<?php

namespace App\Http\Controllers\Administration\Membership;

use App\Http\Controllers\Controller;
use App\Libraries\Membership\MembershipLibrary;
use App\Models\Membership\Permission;
use App\Models\Membership\Role;
use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a role details page
     * @param Request $request
     * @param Role $role
     * @return View
     */
    public function show(Request $request, Role $role): View
    {
        $this->authorize('view', $role);

        $role->loadMissing('permissions', 'users');

        $permissions = Permission::orderBy('id', 'ASC')->get();

        return $this->prepareView('administration.membership.groups.role')
            ->with('permissions', $permissions)
            ->with('role', $role);
    }

    public function addUser(Request $request): array
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        $validated = $request->validate([
            'cid' => 'required',
        ]);

        try {
            $user = User::query()->findOrFail($validated['cid']);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Benutzer mit dieser CID wurde nicht gefunden');
        }
        $role = Role::query()->findOrFail($request->get('roleid'));
        $this->authorize('update', $role);

        if ($user->hasRole($role)) {
            abort(404, 'Benutzer ist bereits Mitglied dieser Gruppe');
        }

        $user = $user->assignRole($role);
        MembershipLibrary::handleMembershipChange($user);

        return [
            'cid' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
        ];
    }

    public function removeUser(Request $request): string
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $validated = $request->validate([
            'cid' => 'required|exists:membership_accounts,id',
        ]);

        $user = User::findOrFail($validated['cid']);
        $role = Role::findOrFail($request->get('roleid'));

        $this->authorize('update', $role);

        $user = $user->removeRole($role);

        MembershipLibrary::handleMembershipChange($user);

        return 'TODO: lang(`administration.role.userRemoved`)';
    }

    /**
     * Toggle a given permission for a given group
     *
     * @param Request $request
     * @param Role $role
     * @param Permission $permission
     * @return string
     */
    public function togglePermission(Request $request): bool
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $role = Role::findOrFail($request->get('roleid'));
        $permission = Permission::findOrFail($request->get('permid'));

        $this->authorize('update', $role);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        // Trigger Membership Library Update for group members
        foreach ($role->users as $u) {
            MembershipLibrary::handleMembershipChange($u);
        }

        return $role->hasPermissionTo($permission) ? 1 : 0;
    }
}
