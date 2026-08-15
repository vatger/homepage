<?php

namespace App\Livewire\Administration;

use App\Libraries\MembershipLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Groups\Permission;
use App\Models\Groups\Team;
use App\Models\Groups\TeamExternalGroup;
use App\Models\Groups\TeamExternalGroupType;
use App\Models\Groups\TeamMembership;
use App\Models\Membership\User;
use App\Models\Membership\UserStaffDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TeamPage extends Component
{
    use NotyTrait;

    #[Locked]
    public Team $team;

    public int $user_id;

    public int $selected_superteam;

    public string $selected_external_group_type = TeamExternalGroupType::ForumGroup->value;

    public string $selected_external_group = '';

    public string $team_title_de = '';

    public string $team_title_en = '';

    public bool $team_show = true;

    public string|int $team_order = 0;

    public string $team_email = '';

    public array $member_settings = [];

    public function mount()
    {
        $this->selected_superteam = $this->team->super_team_id ?? -1;
        $this->team_title_de = $this->team->title_de ?? '';
        $this->team_title_en = $this->team->title_en ?? '';
        $this->team_show = (bool) ($this->team->show ?? true);
        $this->team_order = (int) ($this->team->order ?? 0);
        $this->team_email = $this->team->email ?? '';

        foreach ($this->team->users as $user) {
            $this->member_settings[$user->id] = $this->settingsFromPivot($user->pivot);
        }
    }

    public function boot()
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $external_groups = $this->team->external_groups;
        $external_service_statuses = $external_groups
            ->groupBy(fn (TeamExternalGroup $group): string => $group->external_group_type->value)
            ->map(function ($groups, string $type): array {
                $groupType = TeamExternalGroupType::from($type);
                $available = $groups->contains(function (TeamExternalGroup $group): bool {
                    return filled($group->external_group_name) && $group->external_group_name !== '?';
                });

                return [
                    'label' => str($groupType->name)->headline(),
                    'available' => $available,
                ];
            });

        return view('pages.admin.team')->with([
            'team' => $this->team,
            'subteams' => $this->team->subteams,
            'external_groups' => $external_groups,
            'external_service_statuses' => $external_service_statuses,
            'permissions' => Permission::all(),
            'member_title_recommendations' => [
                'de' => TeamMembership::query()
                    ->whereNotNull('title_de')
                    ->where('title_de', '<>', '')
                    ->distinct()
                    ->orderBy('title_de')
                    ->pluck('title_de'),
                'en' => TeamMembership::query()
                    ->whereNotNull('title_en')
                    ->where('title_en', '<>', '')
                    ->distinct()
                    ->orderBy('title_en')
                    ->pluck('title_en'),
            ],
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
            $this->team->givePermissionTo($permission);
        } else {
            $this->team->revokePermissionTo($permission);
        }
    }

    public function saveTeamDisplaySettings(): void
    {
        $this->authorize('membership.teams.edit');

        $this->team->update([
            'title_de' => $this->team_title_de ?: null,
            'title_en' => $this->team_title_en ?: null,
            'show' => $this->team_show,
            'order' => max(0, (int) ($this->team_order ?: 0)),
            'email' => $this->team_email ?: null,
        ]);

        $this->showNoty('Team-Anzeige gespeichert', 'success');
    }

    public function saveMemberDisplaySettings(int $userId): void
    {
        $this->authorize('membership.teams.edit');

        $settings = $this->member_settings[$userId] ?? [];
        $this->team->users()->updateExistingPivot($userId, [
            'title_de' => ($settings['title_de'] ?? '') ?: null,
            'title_en' => ($settings['title_en'] ?? '') ?: null,
            'show' => (bool) ($settings['show'] ?? true),
            'order' => max(0, (int) ($settings['order'] ?? 0)),
        ]);

        $this->showNoty('Mitgliedsanzeige gespeichert', 'success');
    }

    public function removeUser(int $user_id): void
    {
        $this->authorize('membership.teams.edit.members.subteam-check', $this->team);
        $user = User::findOrFail($user_id);
        $user->removeRole($this->team);
        MembershipLibrary::update($user);
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

        $user->assignRole($this->team);
        $this->member_settings[$user->id] = $this->settingsFromPivot($this->team->users()->whereKey($user->id)->first()?->pivot);
        MembershipLibrary::update($user);
    }

    public function deleteTeam()
    {
        $this->authorize('membership.teams.edit');
        $this->team->delete();

        return Redirect::route('administration.teams')->with('success', 'Team gelöscht');
    }

    public function removeExternalGroup(int $id): void
    {
        $this->authorize('membership.teams.edit');
        TeamExternalGroup::findOrFail($id)->delete();
    }

    public function addExternalGroup(): void
    {
        $this->authorize('membership.teams.edit');
        try {
            $r = new TeamExternalGroup;
            $r->team_id = $this->team->id;
            $r->external_group_type = $this->selected_external_group_type;
            $r->external_group = $this->selected_external_group;
            $r->save();
            $this->showNoty('Rolle hinzugefügt', 'success');
        } catch (\Exception $e) {
            $this->showNoty('Rolle konnte nicht hinzugefügt werden', 'error');
        }
    }

    private function settingsFromPivot(?object $pivot): array
    {
        return [
            'title_de' => $pivot?->title_de ?? '',
            'title_en' => $pivot?->title_en ?? '',
            'show' => (bool) ($pivot?->show ?? true),
            'order' => (int) ($pivot?->order ?? 0),
        ];
    }
}
