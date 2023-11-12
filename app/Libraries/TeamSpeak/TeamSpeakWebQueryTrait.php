<?php

namespace App\Libraries\TeamSpeak;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait TeamSpeakWebQueryTrait
{
    public static function testWebquery()
    {
        self::_sendWebQuery('gm', ['msg' => 'test webquery trait']);
    }

    /**
     * _sendWebQuery
     *
     * @param string $command
     * @param array $queryparams
     * @return mixed
     */
    protected static function _sendWebQuery($command, $queryparams = [])
    {
        $uri = 'http://' . config('teamspeak.host') . ':' . config('teamspeak.webquery_port') . '/' . config('teamspeak.server_number') . '/';

        $_httpClient = new Client([
            'base_uri' => $uri,
            'connect_timeout' => 15,
            'read_tiemout' => 15,
            'timeout' => 30,
            'headers' => [
                'X-Api-Key' => config('teamspeak.apikey'),
            ],
        ]);

        //$queryparams['api-key'] = config('teamspeak.apikey');
        $params = [
            'query' => $queryparams,
        ];
        $response = null;
        try {
            $response = $_httpClient->get($command, $params);
        } catch (GuzzleException $e) {
            Log::error('[TeamSpeakWebQuery] GuzzleException (Code ' . $e->getCode() . '): ' . $e->getMessage());
            return false;
        }

        if ($response?->getStatusCode() == 200) {
            $body = json_decode($response->getBody());

            if (empty($body->status) || $body->status->message != 'ok') {
                return false;
            }
            if (empty($body->body)) {
                return true;
            }

            return $body->body;
        }
        return false;
    }
}
