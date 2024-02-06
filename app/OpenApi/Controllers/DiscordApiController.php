<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User\User;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

/**
 *
 */
#[OpenApi\PathItem]
class DiscordApiController extends ApiController
{
    /**
     * Discord member endpoint
     * @param int $cid the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('discord.find_member')]
    public function find_member(int $cid): object
    {
        $this->authorizeApiRequest('discord.find_member');
        $user = User::find($cid);

        $data = (object) [
            'cid' => $user?->id,
            'is_vatger_member' => !empty($user),
            'is_vatger_fullmember' => $user?->vatgerDetails?->is_vatger_member,
            'atc_rating' => $user?->vatsimDetails?->rating_atc,
            'pilot_rating' => $user?->vatsimDetails?->rating_pilot,
            'teams' => $user
                ?->teams()
                ->map(fn($team) => $team->name)
                ->values()
                ->toArray(),
        ];

        return $data;
    }
}
