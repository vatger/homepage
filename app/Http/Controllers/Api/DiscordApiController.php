<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\Membership\User;

class DiscordApiController extends ApiController
{
    /**
     * Discord member endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     */
    #[ApiPathfinder('discord.find_member')]
    public function find_member(int $cid): object
    {
        $this->authorizeApiRequest('discord.find_member');
        $user = User::find($cid);

        return (object) [
            'cid' => $user?->id,
            'is_vatger_member' => ! empty($user),
            'is_vatger_fullmember' => $user?->vatgerDetails?->is_vatger_member,
            'atc_rating' => $user?->vatsimDetails?->rating_atc,
            'pilot_rating' => $user?->vatsimDetails?->rating_pilot,
            'teams' => $user
                ?->teams()
                ->map(fn ($team) => $team->name)
                ->values()
                ->toArray(),
        ];
    }
}
