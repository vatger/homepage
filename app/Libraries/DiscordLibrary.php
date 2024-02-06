<?php

namespace App\Libraries;

use App\Models\Membership\User\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class DiscordLibrary extends BaseLibrary
{
    protected static function _send(string $endpoint, string $method, array $body = []): object|false
    {
        $client = self::constructClient();
        $uri = 'http://docker.vatsim-germany.org:10000' . '/' . $endpoint;
        try {
            $response = $client->request($method, $uri, ['json' => $body]);
            $response_code = $response?->getStatusCode();
            if ($response_code == 200 || $response_code == 204) {
                return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (GuzzleException | \JsonException $e) {
        }
        return false;
    }

    public static function check_user(User $user): void
    {
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
        $current_hash = hash('md5', json_encode($data));
        if (Cache::get("DiscordLibrary.check_user.hash.$data->cid") == $current_hash) {
            return;
        }
        self::_send('member/update', 'POST', (array) $data);
        //Cache::put("DiscordLibrary.check_user.hash.$data->cid", $current_hash);
    }
}
