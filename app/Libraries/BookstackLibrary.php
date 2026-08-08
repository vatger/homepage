<?php

namespace App\Libraries;

use App\Models\Groups\TeamExternalGroupType;
use App\Models\Membership\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Log;

class BookstackLibrary extends BaseLibrary
{
    // https://demo.bookstackapp.com/api/docs

    protected static function _send(string $endpoint, string $method, array $body = [], array $expected_errors = []): object|false
    {
        $token_id = config('bookstack.token_id');
        $token_secret = config('bookstack.token_secret');

        $headers = ['Authorization' => 'Token '.$token_id.':'.$token_secret];
        $client = self::constructClient([
            'headers' => $headers,
        ]);
        $uri = config('bookstack.host').'/api/'.$endpoint;
        try {
            $response = $client->request($method, $uri, ['json' => $body, 'http_errors' => false]);
            $response_code = $response?->getStatusCode();
            if ($response_code == 200 || $response_code == 204) {
                return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (GuzzleException|\JsonException $e) {
            if (empty($response_code) || ! in_array($response_code, $expected_errors)) {
                Log::error($e->getMessage());
            }
        }

        return false;
    }

    public static function check_user(User $user): void
    {
        $user_data = self::_users_read($user->id);
        if (empty($user_data)) {
            return;
        }
        $roles = $user->external_group_ids(TeamExternalGroupType::BookstackGroup, true);
        $roles[] = config('bookstack.public_role');

        self::_user_update($user->id, $user->email, $roles);
    }

    public static function delete_user(User $user): bool
    {
        $user_data = self::_users_read($user->id);
        if (empty($user_data)) {
            return true;
        }

        return ! empty(self::_send('users/'.$user->id, 'DELETE'));
    }

    public static function get_group_name(int $id): ?string
    {
        $role_list = self::_roles_list();
        if (empty($role_list)) {
            return null;
        }
        foreach ($role_list as $role) {
            if ($role->id == $id) {
                return $role->display_name;
            }
        }

        return null;
    }

    public static function _roles_list(): array|false
    {
        return Cache::remember('BookstackLibrary._roles_list', 120, fn () => self::_send('roles', 'GET')?->data ?? false);
    }

    public static function _users_list(): array|false
    {
        $data = self::_send('users', 'GET');

        return ! $data ? false : $data->data;
    }

    public static function _users_read(int $user_id): object|false
    {
        return self::_send('users/'.$user_id, 'GET', expected_errors: [404]);
    }

    /**
     * @param  array<int>  $role_ids
     */
    public static function _user_update(int $user_id, string $email, array $role_ids = []): bool
    {
        $user_lang = User::find($user_id)->settings->language != 'de' ? 'en' : 'de';
        $body = [
            'name' => strval($user_id),
            'email' => $email,
            'external_auth_id' => strval($user_id),
            'language' => $user_lang,
            'roles' => $role_ids,
        ];

        return ! empty(self::_send('users/'.$user_id, 'PUT', $body));
    }
}
