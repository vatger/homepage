<?php

namespace App\Libraries\Knowledgebase;

trait UsersTrait
{
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
