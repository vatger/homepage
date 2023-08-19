<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\NotyTrait;
use App\Models\Groups\Team;
use App\Models\Membership\User\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class TeamPage extends Component
{
    use NotyTrait;

    public Team $team;
    public int $user_id;

    public function boot()
    {
        $this->authorize('membership.teams.view');
    }

    #[Layout('layouts.admin-master')]
    public function render()
    {
        return view('pages.admin.team')->with(['team' => $this->team, 'permissions' => Permission::all()]);
    }

    public function changePermission(int $permission_id, bool $add): void
    {
        $this->authorize('membership.teams.edit');
        $permission = Permission::findOrFail($permission_id);
        if ($add) {
            $this->team->role->givePermissionTo($permission);
        } else {
            $this->team->role->revokePermissionTo($permission);
        }
    }

    public function removeUser(int $user_id): void
    {
        $this->authorize('membership.teams.edit');
        $user = User::findOrFail($user_id);
        $user->removeRole($this->team->role);
    }

    public function addUser(): void
    {
        $this->authorize('membership.teams.edit');
        $user = User::find($this->user_id ?? null);
        if (!$user) {
            $this->showNoty('CID nicht gefunden', 'error');
            return;
        }
        $user->assignRole($this->team->role);
    }
}
