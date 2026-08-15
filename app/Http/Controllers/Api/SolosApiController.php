<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\Groups\Team;
use App\Models\Membership\User;

class SolosApiController extends ApiController
{
    /**
     * Solo endorsements member endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     */
    #[ApiPathfinder('solos.find_member')]
    public function find_member(int $cid): object
    {
        $this->authorizeApiRequest('solos.find_member');
        $team_a = Team::where('name', 'LIKE', 'ATD Leitung')->firstOrFail();
        $team_p = Team::where('name', 'LIKE', 'ATD Prüfer')->firstOrFail();
        $team_w = Team::where('name', 'LIKE', 'EDWW Mentor')->firstOrFail();
        $team_g = Team::where('name', 'LIKE', 'EDGG Mentor')->firstOrFail();
        $team_m = Team::where('name', 'LIKE', 'EDMM Mentor')->firstOrFail();

        $user = User::find($cid);
        $data = new \stdClass;
        $data->is_vatger_member = ! empty($user);
        $data->is_vatger_atd_lead = $user?->hasRole($team_a);
        $data->is_vatger_atd_examiner = $user?->hasRole($team_p);
        $data->is_vatger_mentor = $user?->hasAnyRole($team_w, $team_g, $team_m);

        return $data;
    }
}
