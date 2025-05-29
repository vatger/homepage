<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\DiscordUser;
use App\Models\Membership\User;
use Illuminate\Http\Request;

class DiscordApiController extends ApiController
{
    /**
     * Discord member endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     */
    #[ApiPathfinder('discord.find_member')]
    #[\Deprecated]
    public function find_member(int $cid, Request $request): object
    {
        $this->authorizeApiRequest('discord.find_member');
        $user = User::find($cid);
        $discord_id = $request->input('discord_id', null);

        if ($discord_id) {
            DiscordUser::where('discord_id', $discord_id)->delete();
            DiscordUser::where('user_id', $cid)->delete();
            $discord_user = new DiscordUser;
            $discord_user->user_id = $cid;
            $discord_user->discord_id = $discord_id;
            $discord_user->save();
        }

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

    /**
     * Discord get member endpoint
     */
    #[ApiPathfinder('discord.get_member')]
    public function get_member(string $discord_id, Request $request): ?object
    {
        $this->authorizeApiRequest('discord.get_member');
        $discord_user = DiscordUser::where('discord_id', $discord_id)->first();

        if (! $discord_user || ! $discord_user->user_id) {
            if (CoreApiLibrary2::checkLimit() > 0) {
                CoreApiLibrary2::findDiscord($discord_id);
                $discord_user = DiscordUser::where('discord_id', $discord_id)->first();
            }
        }

        if (! $discord_user || ! $discord_user->user_id) {
            return null;
        }

        $user = User::find($discord_user->user_id);

        if (! $user) {
            return null;
        }

        return (object) [
            'cid' => $user->id,
            'discord_id' => $discord_id,
            'is_vatger_member' => ! empty($user),
            'is_vatger_fullmember' => $user?->vatgerDetails?->is_vatger_member,
            'atc_rating' => $user->vatsimDetails?->rating_atc,
            'pilot_rating' => $user->vatsimDetails?->rating_pilot,
            'teams' => $user
                ->teams()
                ->map(fn ($team) => $team->name)
                ->values()
                ->toArray(),
        ];
    }

    /**
     * Discord add member endpoint
     */
    #[ApiPathfinder('discord.add_member')]
    public function add_member(Request $request): void
    {
        $this->authorizeApiRequest('discord.add_member');
        $req = $request->validate([
            'cid' => 'integer',
            'discord_id' => 'required|string',
        ]);
        $cid = $req['cid'] ?? null;
        $discord_id = $req['discord_id'];
        if ($cid) {
            DiscordUser::where('user_id', $cid)->delete();
        }
        $discord_user = DiscordUser::where('discord_id', $discord_id)->firstOrNew();
        if ($cid) {
            $discord_user->user_id = $cid;
        }
        $discord_user->discord_id = $discord_id;
        $discord_user->save();
    }
}
