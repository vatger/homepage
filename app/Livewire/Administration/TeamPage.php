<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\NotyTrait;
use App\Models\Groups\Team;
use App\Models\Membership\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class TeamPage extends Component
{
    use NotyTrait;

    #[Locked]
    public Team $team;

    public int $user_id;
    public int $selected_superteam;

    public function mount()
    {
        $this->selected_superteam = $this->team->super_team_id ?? -1;
    }

    public function boot()
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        return view('pages.admin.team')->with([
            'team' => $this->team,
            'subteams' => $this->team->subteams,
            'permissions' => Permission::all(),
        ]);
    }

    public function updated($name, $value): void
    {
        if ($name == 'selected_superteam') {
            $this->authorize('membership.teams.edit.permissions');
            $st = Team::find($this->selected_superteam);
            if (empty($st) || $st->super_team_id == $this->team->id || $st->id == $this->team->id) {
                $st = null;
                $this->showNoty('Diese Gruppe kann nicht ausgewählt werden!', 'error');
            }
            $this->team->super_team_id = $st?->id;
            $this->team->save();
            $this->selected_superteam = $st?->id ?? -1;
        }
    }

    public function changePermission(int $permission_id, bool $add): void
    {
        $this->authorize('membership.teams.edit.permissions');
        $permission = Permission::findOrFail($permission_id);
        if ($add) {
            $this->team->role->givePermissionTo($permission);
        } else {
            $this->team->role->revokePermissionTo($permission);
        }
    }

    public function removeUser(int $user_id): void
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
        $user = User::findOrFail($user_id);
        $user->removeRole($this->team->role);
    }

    public function addUser(): void
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
        $user = User::find($this->user_id ?? null);
        if (!$user) {
            $this->showNoty('CID nicht gefunden', 'error');
            return;
        }
        $user->assignRole($this->team->role);
    }

    public function deleteTeam(): RedirectResponse
    {
        $this->authorize('membership.teams.edit.permissions');
        $this->team->delete();
        return Redirect::route('administration.teams')->with('success', 'Team gelöscht');
    }
}
