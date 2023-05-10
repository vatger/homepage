<?php

namespace App\Libraries\VATSIM;

use App\Models\Membership\User\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class APILibrary
{
    /**
     * Cache all assigned subdivision members for 24 hours
     *
     * @return Array|Bool The cached member objects or false if request failed
     */
    public static function CachedSubdivisionMembers()
    {
        return Cache::remember('net.vatsim.api.subdivisions.ger', 24 * 60 * 60, function () {
            $members = self::FetchData('subdivisions/GER/members');
            return $members;
        });
    }

    /**
     * This will retrieve all members assigned to the subdivision
     *
     * @deprecated This function shall not be used anymore.
     * @return Array|Bool The members or false if request failed
     */
    public static function SubdivisionMembers()
    {
        return self::FetchData('subdivisions/GER/members');
    }

    /**
     * Get the members rating times
     *
     * @param User $account The account to lookup
     * @return Object JSON decoded rating times
     */
    public static function RatingTimes(User $user)
    {
        // For testing: override id to an existing one
        if (env('APP_ENV') !== 'production') {
            $cid = 1289607;
            return self::FetchDataUnauthorized('ratings/' . $cid . '/rating_times');
        }
        return self::FetchDataUnauthorized('ratings/' . $user->id . '/rating_times');
    }

    /**
     * Internal protected function to call the VATSIM.net api endpoints
     *
     * @param String $urlEndpoint The endpoint to call
     * @return Bool|Array The Resulting data or false if the status was anything other than 200
     */
    private static function FetchData($urlEndpoint)
    {
        // Example request
        // curl -H 'Accept: application/json; indent=4' -H "Authorization: Token 7796bdb6b0be14ccf3f147036e133f1719d4712b" "https://api.vatsim.net/api/divisions/test/members/"
        $client = new Client([
            'base_uri' => config('vatsim.api.base'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json; indent=4',
                'Authorization' => 'Token ' . config('vatsim.api.token'),
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
        }
        return false;
    }

    /**
     * This will call the VATSIM.net API in an unauthorized manner.
     *
     * @param String $urlEndpoint The endpoint to call
     * @return Bool|Array The resulting data from the api. Or false if the request failed
     */
    private static function FetchDataUnauthorized($urlEndpoint)
    {
        // Example request
        // curl -H 'Accept: application/json; indent=4' "https://api.vatsim.net/api/divisions/test/members/"
        $client = new Client([
            'base_uri' => config('vatsim.api.base'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json; indent=4',
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
            Log::critical('[APILibrary::FetchDataUnauthorized]' . $e->getMessage());
        }
        return false;
    }
}
