<?php

namespace App\Models\Membership\Concerns;

use App\Models\Groups\Team;
use App\Models\Groups\TeamExternalGroup;
use App\Models\Groups\TeamExternalGroupType;
use Illuminate\Support\Collection;

trait HasTeamConcern
{
    public function teams(): array|Collection|Team
    {
        return Team::whereIntegerInRaw('id', $this->team_ids())->get();
    }

    public function external_groups(?TeamExternalGroupType $external_group_type = null): array|Collection|TeamExternalGroup
    {
        if (! $external_group_type) {
            return TeamExternalGroup::whereIntegerInRaw('team_id', $this->team_ids())->get();
        }

        return TeamExternalGroup::whereIntegerInRaw('team_id', $this->team_ids())
            ->where('external_group_type', 'LIKE', $external_group_type->value)
            ->get();
    }

    public function external_group_ids(TeamExternalGroupType $external_group_type, bool $cast_to_int = false): array
    {
        if (! $this->staffDetails || $this->staffDetails?->accepted_data_protection_at || ! config('api_sync_active.sdp_enforce')) {
            return $this->external_groups($external_group_type)
                ->map(function ($r) use ($cast_to_int) {
                    if ($cast_to_int) {
                        return intval($r->external_group);
                    }

                    return $r->external_group;
                })
                ->unique()
                ->values()
                ->toArray();
        } else {
            return [];
        }
    }

    private function team_ids(): array
    {
        return $this->roles()
            ->wherePivot('model_type', self::class)
            ->pluck((new Team)->getTable().'.id')
            ->toArray();
    }
}
