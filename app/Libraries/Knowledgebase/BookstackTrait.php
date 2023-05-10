<?php

namespace App\Libraries\Knowledgebase;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

trait BookstackTrait
{
    protected static function _send(string $endpoint, string $method, array $body = []): object|false
    {
        $uri = 'https://' . config('bookstack.host') . '/api';
        $token_id = config('bookstack.token_id');
        $token_secret = config('bookstack.token_secret');

        $headers = ['Authorization' => 'Token ' . $token_id . ':' . $token_secret];

        $client = new Client([
            'base_uri' => $uri,
            'connect_timeout' => 15,
            'read_timeout' => 15,
            'timeout' => 30,
            'headers' => $headers,
        ]);

        try {
            $response = $client->request($method, $endpoint, ['json' => $body]);

            if ($response?->getStatusCode() == 200 || $response?->getStatusCode() == 204) {
                return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (GuzzleException | JsonException $e) {
        }
        return false;
    }
}
