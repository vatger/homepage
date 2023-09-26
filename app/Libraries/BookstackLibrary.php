<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BookstackLibrary
{
    # https://demo.bookstackapp.com/api/docs

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

    public static function _users_list(): array|false
    {
        $data = self::_send('users', 'GET');
        return !$data ? false : $data->data;
    }

    public static function _users_read(int $user_id): object|false
    {
        return self::_send('users/' . $user_id, 'GET');
    }

    public static function _users_update(int $user_id, array $role_ids = []): bool
    {
        return !empty(self::_send('users/' . $user_id, 'PUT', ['roles' => $role_ids]));
    }
}
