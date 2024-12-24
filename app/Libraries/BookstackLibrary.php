<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class BookstackLibrary extends BaseLibrary
{
    # https://demo.bookstackapp.com/api/docs

    protected static function _send(string $endpoint, string $method, array $body = []): object|false
    {
        $token_id = config('bookstack.token_id');
        $token_secret = config('bookstack.token_secret');

        $headers = ['Authorization' => 'Token ' . $token_id . ':' . $token_secret];
        $client = self::constructClient([
            'headers' => $headers,
        ]);
        $uri = config('bookstack.host') . '/api/' . $endpoint;
        try {
            $response = $client->request($method, $uri, ['json' => $body]);
            $response_code = $response?->getStatusCode();
            if ($response_code == 200 || $response_code == 204) {
                return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (GuzzleException|\JsonException $e) {
        }
        return false;
    }

    public static function check_user(User $user): void
    {
        $user_data = self::_users_read($user->id);
        if (empty($user_data)) {
            return;
        }
        $roles = $user->service_role_ids(ServiceRoleType::BookstackGroup, true);
        $roles[] = config('bookstack.public_role');

        //$current_roles = collect($user_data->roles)->map(fn($r) => $r->id)->flatten()->toArray();

        self::_user_update($user->id, $roles);
    }

    public static function delete_user(User $user): bool
    {
        return false;
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

    static function _roles_list(): array|false
    {
        return Cache::remember('BookstackLibrary._roles_list', 120, fn() => self::_send('roles', 'GET')?->data ?? false);
    }

    static function _users_list(): array|false
    {
        $data = self::_send('users', 'GET');
        return !$data ? false : $data->data;
    }

    static function _users_read(int $user_id): object|false
    {
        return self::_send('users/' . $user_id, 'GET');
    }

    static function _user_update(int $user_id, array $role_ids = []): bool
    {
        $body = [
            'roles' => $role_ids,
            'name' => $user_id,
        ];
        return !empty(self::_send('users/' . $user_id, 'PUT', $body));
    }


}
