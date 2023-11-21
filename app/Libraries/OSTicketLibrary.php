<?php

namespace App\Libraries;

use App\Libraries\BaseLibrary;
use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class OSTicketLibrary extends BaseLibrary
{
    public static function send(string $method, string $endpoint, array $data = []): false|Response
    {
        $client = self::constructClient([
            'header' => [
                'Accept' => 'application/json',
                'Authorization' => 'Token ' . config('osticket.token'),
            ],
        ]);
        $uri = config('osticket.url') . '/' . $endpoint;

        try {
            return $client->request($method, $uri, ['form_params' => $data]);
        } catch (GuzzleException $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public static function check_user(User $user): void
    {
        $result = self::send('POST', 'user/syncUserGroups', [
            'user_id' => strval($user->id),
            'dept_ids' => $user->service_role_ids(ServiceRoleType::SupportGroup, cast_to_int: true),
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
        ]);
        $result_data = json_decode($result->getBody()->getContents());
        if ($result_data?->usercreated) {
            $user->notify(
                new BasicNotification(
                    'Dein Account im Ticketsystem',
                    "Es wurde ein Account für dich im Ticketsystem angelegt. Dein Loginname lautet: v$user->id mit der Email: $user->email.  Nutze die Funktion 'Passwort vergessen' um dein Passwort zurückzusetzen.",
                    'Tech Leitung',
                    'hier gehts zum Agentenlogin',
                    'https://support.vatsim-germany.org/scp/',
                    Carbon::now()->addDays(14),
                    Carbon::now()->addDays(365),
                ),
            );
        }
    }
}
