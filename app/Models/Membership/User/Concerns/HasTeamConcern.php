<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Groups\ServiceRole;
use App\Models\Groups\ServiceRoleType;
use App\Models\Groups\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait HasTeamConcern
{
    public function teams(): array|Collection|Team
    {
        return Team::whereIntegerInRaw('id', $this->team_ids())->get();
    }

    public function service_roles(?ServiceRoleType $service_type = null): array|Collection|ServiceRole
    {
        if (!$service_type) {
            return ServiceRole::whereIntegerInRaw('team_id', $this->team_ids())->get();
        }
        return ServiceRole::whereIntegerInRaw('team_id', $this->team_ids())
            ->where('service_type', 'LIKE', $service_type->value)
            ->get();
    }

    public function service_role_ids(ServiceRoleType $service_type, bool $cast_to_int = false): array
    {
        if (!$this->staffDetails || $this->staffDetails?->accepted_data_protection_at || !config('api_sync_active.sdp_enforce')) {
            return $this->service_roles($service_type)
                ->map(function ($r) use ($cast_to_int) {
                    if ($cast_to_int) {
                        return intval($r->service_role);
                    }
                    return $r->service_role;
                })
                ->unique()
                ->toArray();
        } else {
            return [];
        }
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
