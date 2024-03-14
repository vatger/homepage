<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use App\Libraries\MembershipLibrary;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class VATEUDCoreLibrary extends BaseLibrary
{
    public static string $cache_key_user = 'CoreApiLibrary2.last_member_refresh.';

    public static function send(string $type, string $endpoint, array $data = []): array|object|null
    {
        $apikey = config('vatsim.vateud.token');
        $uri = config('vatsim.vateud.base') . '/' . ltrim($endpoint, '/');
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

    public static function roster(): object|null
    {
        return self::send("GET", "roster")->data;
    }

}
