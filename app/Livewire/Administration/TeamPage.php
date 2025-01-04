<?php

namespace App\Livewire\Administration;

use App\Libraries\MembershipLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Groups\ServiceRole;
use App\Models\Groups\ServiceRoleType;
use App\Models\Groups\Team;
use App\Models\Membership\User;
use App\Models\Membership\UserStaffDetail;
use Carbon\Carbon;
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

    public string $selected_service_role_type = ServiceRoleType::ForumGroup->value;

    public string $selected_service_role = '';

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
            'service_roles' => $this->team->service_roles,
            'permissions' => Permission::all(),
        ]);
    }

    public function updated($name, $value): void
    {
        if ($name == 'selected_superteam') {
            $this->authorize('membership.teams.edit');
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
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
        $user = User::findOrFail($user_id);
        $user->removeRole($this->team->role);
        MembershipLibrary::update($user, async: true);
        if (count($user->roles) == 0) {
            $user->staffDetails->leaving_staff_at = Carbon::now()->addDays(30);
            $user->staffDetails->save();
        }
    }

    public function addUser(): void
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
        $user = User::find($this->user_id ?? null);
        if (! $user) {
            $this->showNoty('CID nicht gefunden', 'error');

            return;
        }

        if (! $user->staffDetails) {
            $sd = new UserStaffDetail;
            $sd->user_id = $user->id;
            $sd->joined_staff_at = now();
            $sd->staff_email = strtolower(substr($user->firstname, 0, 1).'.'.$user->lastname.'@vatger.de');
            $sd->save();
        } else {
            if ($user->staffDetails->leaving_staff_at) {
                $user->staffDetails->leaving_staff_at = null;
                $user->staffDetails->save();
            }
        }

        $user->assignRole($this->team->role);
        MembershipLibrary::update($user, async: true);
    }

    public function deleteTeam()
    {
        $this->authorize('membership.teams.edit');
        $this->team->delete();

        return Redirect::route('administration.teams')->with('success', 'Team gelöscht');
    }

    public function removeServiceRole(int $id): void
    {
        $this->authorize('membership.teams.edit');
        ServiceRole::findOrFail($id)->delete();
    }

    public function addServiceRole(): void
    {
        $this->authorize('membership.teams.edit');
        try {
            $r = new ServiceRole;
            $r->team_id = $this->team->id;
            $r->service_type = $this->selected_service_role_type;
            $r->service_role = $this->selected_service_role;
            $r->save();
            $this->showNoty('Rolle hinzugefügt', 'success');
        } catch (\Exception $e) {
            $this->showNoty('Rolle konnte nicht hinzugefügt werden', 'error');
        }
    }
}
