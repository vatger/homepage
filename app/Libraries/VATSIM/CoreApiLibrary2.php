<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use App\Libraries\GDPRLibrary;
use App\Models\Membership\DiscordUser;
use App\Models\Membership\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CoreApiLibrary2 extends BaseLibrary
{
    private static string $last_requests_key = 'CoreApiLibrary2.counter';

    private static int $max_request = 10;

    private static int $seconds = 65;

    public static function checkLimit(): int
    {
        $last_requests = Cache::get(self::$last_requests_key, []);
        $last_requests = array_filter($last_requests, fn ($key) => floatval($key) > microtime(true) - self::$seconds, ARRAY_FILTER_USE_KEY);
        Cache::put(self::$last_requests_key, $last_requests);

        return self::$max_request - count($last_requests);
    }

    public static function send(string $type, string $endpoint, array $data = [], bool $force = false): array|object|null
    {
        if (! $force && self::checkLimit() <= 0) {
            Log::info('CoreApiLibrary2: limit exceeded');

            return null;
        }

        $apikey = config('vatsim.api.token2');
        $uri = config('vatsim.api.host').'/v2/'.ltrim($endpoint, '/');
        $type = strtoupper($type);
        $client = self::constructClient([
            'headers' => [
                'Accept' => 'application/json',
                'X-API-Key' => $apikey,
            ],
        ]);
        $res = null;
        try {
            if (empty($data)) {
                $res = $client->request($type, $uri);
            } elseif ($type != 'GET') {
                $res = $client->request($type, $uri, ['json' => $data]);
            } else {
                $res = $client->request($type, $uri, ['query' => $data]);
            }

            $last_requests = Cache::get(self::$last_requests_key, []);
            $last_requests[strval(microtime(true))] = $type.':'.$endpoint.':'.implode(',', $data);
            Cache::put(self::$last_requests_key, $last_requests);

        } catch (GuzzleException $e) {
            if ($e->getCode() == 429) {

                $last_requests = Cache::get(self::$last_requests_key, []);
                for ($i = 0; $i < self::$max_request + 1; $i++) {
                    $last_requests[strval(microtime(true) + 1e-6 * $i)] = 'rate limit exceeded '.$i;
                }
                Cache::put(self::$last_requests_key, $last_requests);

                Log::info('CoreApiLibrary2: limit exceeded by code 429');
            }

            return null;
        }
        $json = json_decode($res?->getBody()?->getContents());

        return $json;
    }

    public static function findDiscord(string $discord_id): void
    {
        $result = self::send('GET', "members/discord/$discord_id");
        if (empty($result)) {
            return;
        }
        if (isset($result->user_id) && isset($result->id)) {
            DiscordUser::where('user_id', $result->user_id)->delete();
            DiscordUser::where('discord_id', $result->id)->delete();
            $discord_user = new DiscordUser;
            $discord_user->user_id = $result->user_id;
            $discord_user->discord_id = $result->id;
            $discord_user->save();
        }

    }

    public static function downloadMember(User $user): void
    {
        $result = self::send('GET', "members/$user->id");
        if (empty($result)) {
            return;
        }
        $start_time = Carbon::now()->timestamp;
        Storage::put("jobs/members/$start_time+$result->id.json", json_encode($result));
    }

    public static function downloadSubdivisionMembers(int &$offset, int $limit = 100): void
    {
        $result = self::send('GET', 'orgs/subdivision/GER', [
            'include_inactive' => true,
            'limit' => $limit,
            'offset' => $offset,
        ]);
        if (empty($result)) {
            return;
        }

        $start_time = Carbon::now()->timestamp;

        $offset = $offset + $limit;

        if ($result->count <= $offset) {
            $offset = 0;
        }
        foreach ($result->items as $member) {
            Storage::put("jobs/members/$start_time+$member->id.json", json_encode($member));
        }
    }

    public static function insertMemberData(?User $user, ?object $data, int $timestamp): void
    {
        if (empty($data) || empty($user)) {
            return;
        }

        if (isset($data->name_first) && isset($data->name_last) && ! GDPRLibrary::is_currently_locked($user)) {
            $user->update([
                'firstname' => $data->name_first,
                'lastname' => $data->name_last,
                'email' => $data->email ?? $user->email,
            ]);
        }
        $user->vatsimDetails->update([
            'rating_atc' => $data->rating,
            'rating_pilot' => $data->pilotrating,
            'rating_military' => $data->militaryrating,
            'region_code' => $data->region_id,
            'region_name' => $user->vatsimDetails->region_code == $data->region_id ? $user->vatsimDetails->region_name : '',
            'division_code' => $data->division_id,
            'division_name' => $user->vatsimDetails->division_code == $data->division_id ? $user->vatsimDetails->division_name : '',
            'subdivision_code' => $data->subdivision_id,
            'subdivision_name' => $user->vatsimDetails->subdivision_code == $data->subdivision_id ? $user->vatsimDetails->subdivision_name : '',
            'last_rating_change_at' => $data->lastratingchange ? Carbon::parse($data->lastratingchange) : $user->vatsimDetails->last_rating_change_at,
            'registered_at' => $data->reg_date ? Carbon::parse($data->reg_date) : $user->vatsimDetails->registered_at,
            'updated_at' => Carbon::now(),
            'last_download' => $timestamp,
        ]);
    }
}
