<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use App\Libraries\MembershipLibrary;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class CoreApiLibrary2 extends BaseLibrary
{
    public static string $cache_key_user = 'CoreApiLibrary2.last_member_refresh.';

    public static function send(string $type, string $endpoint, array $data = []): array|object|null
    {
        $apikey = config('vatsim.api.token2');
        $uri = config('vatsim.api.host') . '/v2/' . ltrim($endpoint, '/');
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
        } catch (GuzzleException $e) {
            return null;
        }
        $json = json_decode($res?->getBody()?->getContents());
        return $json;
    }

    private static function cache_expired(string $key, int $max_cache_time): bool
    {
        if (!Cache::has($key) || $max_cache_time <= 0) {
            return true;
        }
        $saved_timestamp = Cache::get($key);
        $diff = Carbon::now()->timestamp - $saved_timestamp;
        return $diff > $max_cache_time;
    }

    public static function updateMember(User $user, int $max_cache_time = 60 * 60 * 12, bool $update_vatger_membership = false): void
    {
        $cache_key = self::$cache_key_user . $user->id;
        if (!self::cache_expired($cache_key, $max_cache_time)) {
            return;
        }
        $result = self::send('GET', "members/$user->id");
        self::insertMemberData($user, $result, $update_vatger_membership);
    }

    public static function updateSubdivisionMembers(int $offset, int $limit = 100): int
    {
        $result = self::send('GET', 'orgs/subdivision/GER', [
            'include_inactive' => true,
            'limit' => $limit,
            'offset' => $offset,
        ]);
        if (empty($result)) {
            return $offset;
        }
        $count = $result->count;
        $new_offset = $offset;
        foreach ($result->items as $data) {
            $user = User::find($data->id);
            if ($user) {
                self::insertMemberData($user, $data, membership_refresh: true);
            }
            $new_offset++;
        }

        if ($new_offset >= $count) {
            $new_offset = 0;
        }
        return $new_offset;
    }

    private static function insertMemberData(?User $user, ?object $data, bool $membership_refresh = false): void
    {
        if (empty($data) || empty($user)) {
            return;
        }
        if (isset($data->name_first) && isset($data->name_last) && isset($data->email)) {
            $user->update([
                'firstname' => $data->name_first,
                'lastname' => $data->name_last,
                'email' => $data->email,
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
            'last_rating_change_at' => $data->lastratingchange,
            'registered_at' => $data->reg_date ?? $user->vatsimDetails->registered_at,
            'updated_at' => Carbon::now(),
        ]);
        $cache_key = self::$cache_key_user . $user->id;
        Cache::put($cache_key, Carbon::now()->timestamp);
        if ($membership_refresh) {
            MembershipLibrary::update($user, api_refresh: false);
        }
    }
}
