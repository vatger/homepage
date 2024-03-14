<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use GuzzleHttp\Exception\GuzzleException;

class VATEUDCoreLibrary extends BaseLibrary
{


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
        return self::send("GET", "facility/roster")?->data;
    }

}
