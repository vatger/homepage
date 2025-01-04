<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use App\Libraries\GDPRLibrary;
use App\Libraries\MembershipLibrary;
use App\Models\Membership\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class CoreApiLibrary2 extends BaseLibrary
{
    public static string $cache_key_user = 'CoreApiLibrary2.last_member_refresh.';

    public static function send(string $type, string $endpoint, array $data = []): array|object|null
    {
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
        } catch (GuzzleException $e) {
            return null;
        }
        $json = json_decode($res?->getBody()?->getContents());

        return $json;
    }

    private static function cache_expired(string $key, int $max_cache_time): bool
    {
        if (! Cache::has($key) || $max_cache_time <= 0) {
            return true;
        }
        $saved_timestamp = Cache::get($key);
        $diff = Carbon::now()->timestamp - $saved_timestamp;

        return $diff > $max_cache_time;
    }

    public static function updateMember(User $user, int $max_cache_time = 60 * 60 * 12, bool $update_vatger_membership = false): void
    {
        $obj = self::downloadMember($user, $max_cache_time);
        if (! $obj) {
            return;
        }
        self::insertMemberData($user, $obj, $update_vatger_membership);
    }

    public static function downloadMember(User $user, int $max_cache_time = 60 * 60 * 12): ?object
    {
        $cache_key = self::$cache_key_user.$user->id;
        if (! self::cache_expired($cache_key, $max_cache_time)) {
            return null;
        }

        return self::send('GET', "members/$user->id");
    }

    public static function updateSubdivisionMembers(int $offset, int $limit = 100): int
    {
        $items = self::downloadSubdivisionMembers($offset, $limit);
        foreach ($items as $data) {
            $user = User::find($data->id);
            if ($user) {
                self::insertMemberData($user, $data, membership_refresh: true);
            }
        }

        return $offset;
    }

    public static function downloadSubdivisionMembers(int &$offset, int $limit = 100): array
    {
        $result = self::send('GET', 'orgs/subdivision/GER', [
            'include_inactive' => true,
            'limit' => $limit,
            'offset' => $offset,
        ]);
        if (empty($result)) {
            return [];
        }
        $offset = $offset + $limit;
        if ($result->count <= $offset) {
            $offset = 0;
        }

        return $result->items;
    }

    public static function insertMemberData(?User $user, ?object $data, bool $membership_refresh = false, ?int $timestamp = null): void
    {
        if (empty($data) || empty($user)) {
            return;
        }

        $cache_key = self::$cache_key_user.$user->id;

        if (Cache::has($cache_key) && Cache::has($cache_key) > $timestamp) {
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
        ]);

        Cache::put($cache_key, $timestamp ?? Carbon::now()->timestamp);
        if ($membership_refresh) {
            MembershipLibrary::update($user, api_refresh: false);
        }
    }
}
