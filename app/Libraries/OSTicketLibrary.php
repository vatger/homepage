<?php

namespace App\Libraries;

use App\Libraries\BaseLibrary;
use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class OSTicketLibrary extends BaseLibrary
{
    public static function send(string $method, string $endpoint, array $data = []): false|Response
    {
        $client = self::constructClient([
            'header' => [
                'Authorization' => 'Token ' . config('osticket.token'),
            ],
        ]);
        $uri = config('osticket.url') . '/' . $endpoint;

        try {
            return $client->request($method, $uri, ['data' => $data]);
        } catch (GuzzleException $e) {
            return false;
        }
    }

    public static function check_user(User $user)
    {
        $result = self::send('POST', 'user/syncUserGroups', [
            'user_id' => strval($user->id),
            'dept_ids' => $user->service_role_ids(ServiceRoleType::SupportGroup, cast_to_int: true),
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
        ]);

        $result_data = json_decode($result->getBody()->getContents());
        dd($result_data);
    }
}
