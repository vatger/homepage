<?php

namespace App\OpenApi\Controllers;

use App\Models\Groups\Team;
use App\Models\Membership\User;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

/**
 *
 */
#[OpenApi\PathItem]
class SolosApiController extends ApiController
{
    /**
     * Solo endorsements member endpoint
     * @param int $cid the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('solos.find_member')]
    public function find_member(int $cid): object
    {
        $this->authorizeApiRequest('solos.find_member');
        $team_a = Team::where('name', 'LIKE', 'ATD Leitung')->firstOrFail()?->role;
        $team_p = Team::where('name', 'LIKE', 'ATD Prüfer')->firstOrFail()?->role;
        $team_w = Team::where('name', 'LIKE', 'EDWW Mentor')->firstOrFail()?->role;
        $team_g = Team::where('name', 'LIKE', 'EDGG Mentor')->firstOrFail()?->role;
        $team_m = Team::where('name', 'LIKE', 'EDMM Mentor')->firstOrFail()?->role;

        $user = User::find($cid);
        $data = new \stdClass();
        $data->is_vatger_member = !empty($user);
        $data->is_vatger_atd_lead = $user?->hasRole($team_a);
        $data->is_vatger_atd_examiner = $user?->hasRole($team_p);
        $data->is_vatger_mentor = $user?->hasAnyRole($team_w, $team_g, $team_m);

        return $data;
    }
}
