<?php

namespace App\Libraries\VATSIM;

use App\Models\Membership\User\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class APILibrary
{
    /**
     * Cache all assigned subdivision members for 20 min
     *
     * @return false|array The cached member objects or false if request failed
     */
    public static function CachedSubdivisionMembers(): false|array
    {
        return Cache::remember('net.vatsim.api.subdivisions.ger', 20 * 60, function () {
            return self::SubdivisionMembers();
        });
    }

    /**
     * This will retrieve all members assigned to the subdivision
     *
     * @return false|array The members or false if request failed
     */
    public static function SubdivisionMembers(): false|array
    {
        return self::FetchData('subdivisions/GER/members', true);
    }

    /**
     * Get the members data
     *
     * @param User $user The account to lookup
     * @return false|object JSON decoded rating times
     */
    public static function Member(User $user): false|object
    {
        // For testing: override id to an existing one
        if (env('APP_ENV', 'dev') != 'production') {
            $cid = 1289607;
            $data = self::FetchData('ratings/' . $cid, true);
            $data->email = '' . $user->email;
            return $data;
        }
        return self::FetchData('ratings/' . $user->id, true);
    }

    public static function MemberUpdate(User $user, bool $cache = true): bool
    {
        $cache_key = 'vatsim.api.member_update.' . $user->id;
        if ($cache && Cache::has($cache_key)) {
            return true;
        }
        $data = self::Member($user);
        if (!$data) {
            return false;
        }
        $user->update([
            'firstname' => $data->name_first,
            'lastname' => $data->name_last,
            'email' => $data->email,
        ]);
        $user->vatsimDetails->update([
            'rating_atc' => $data->rating,
            'rating_pilot' => $data->pilotrating,
            'rating_military' => $data->militaryrating,
            'region_code' => $data->region,
            'region_name' => $user->vatsimDetails->region_code == $data->region ? $user->vatsimDetails->region_name : '',
            'division_code' => $data->division,
            'division_name' => $user->vatsimDetails->division_code == $data->division ? $user->vatsimDetails->division_name : '',
            'subdivision_code' => $data->subdivision,
            'subdivision_name' => $user->vatsimDetails->subdivision_code == $data->subdivision ? $user->vatsimDetails->subdivision_name : '',
            'last_rating_change_at' => $data->lastratingchange,
        ]);
        Cache::put($cache_key, Carbon::now(), 60 * 10);
        return true;
    }

    /**
     * Get the members rating times
     *
     * @param User $user The account to lookup
     * @return false|object JSON decoded rating times
     */
    public static function RatingTimes(User $user): false|object
    {
        // For testing: override id to an existing one
        if (env('APP_ENV') != 'production') {
            $cid = 1289607;
            return self::FetchData('ratings/' . $cid . '/rating_times');
        }
        return self::FetchData('ratings/' . $user->id . '/rating_times');
    }

    /**
     * Internal protected function to call the VATSIM.net api endpoints
     *
     * @param string $urlEndpoint The endpoint to call
     * @param bool $with_token
     * @return false|array|object The Resulting data or false if the status was anything other than 200
     */
    private static function FetchData(string $urlEndpoint, bool $with_token = false): false|array|object
    {
        // Example request
        // curl -H 'Accept: application/json; indent=4' -H "Authorization: Token 7796bdb6b0be14ccf3f147036e133f1719d4712b" "https://api.vatsim.net/api/divisions/test/members/"
        $client = new Client([
            'base_uri' => config('vatsim.api.base'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json; indent=4',
                'Authorization' => $with_token ? 'Token ' . config('vatsim.api.token') : null,
            ],
            'connect_timeout' => 25,
        ]);

        try {
            $endpoint = config('vatsim.api.base') . '/' . $urlEndpoint;
            $response = $client->get($endpoint);
            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody());
            }
        } catch (\Exception $e) {
            Log::critical('[APILibrary::FetchData]' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::critical('[APILibrary::FetchData]' . $e->getMessage());
        }
        return false;
    }
}
