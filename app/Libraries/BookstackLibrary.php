<?php

namespace App\Libraries;

use App\Models\Membership\User\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BookstackLibrary extends BaseLibrary
{
    # https://demo.bookstackapp.com/api/docs

    protected static function _send(string $endpoint, string $method, array $body = []): object|false
    {
        $uri = config('bookstack.host') . '/api';
        $token_id = config('bookstack.token_id');
        $token_secret = config('bookstack.token_secret');

        $headers = ['Authorization' => 'Token ' . $token_id . ':' . $token_secret];
        $client = self::constructClient([
            'base_uri' => $uri,
            'headers' => $headers,
        ]);

        try {
            $response = $client->request($method, $endpoint, ['json' => $body]);
            $response_code = $response?->getStatusCode();
            if ($response_code == 200 || $response_code == 204) {
                return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (GuzzleException | \JsonException $e) {
        }
        return false;
    }

    public static function check_user(User $user): void
    {
        $user_data = self::_users_read($user->id);
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
