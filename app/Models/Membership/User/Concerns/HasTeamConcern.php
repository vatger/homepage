<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Groups\ServiceRole;
use App\Models\Groups\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait HasTeamConcern
{
    public function teams(): array|Collection|Team
    {
        return Team::whereIntegerInRaw('id', $this->team_ids())->get();
    }

    public function service_roles(?string $service_type = null): array|Collection|ServiceRole
    {
        if (!$service_type) {
            return ServiceRole::whereIntegerInRaw('team_id', $this->team_ids())->get();
        }
        return ServiceRole::whereIntegerInRaw('team_id', $this->team_ids())
            ->where('service_type', 'LIKE', $service_type)
            ->get();
    }

    private function team_ids(): array
    {
        return DB::table('teams')
            ->join('roles', 'teams.role_id', '=', 'roles.id')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', '=', $this->id)
            ->select('teams.id')
            ->get()
            ->map(function ($team) {
                return $team->id;
            })
            ->toArray();
    }
}
