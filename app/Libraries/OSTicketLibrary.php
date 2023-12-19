<?php

namespace App\Libraries;

use App\Libraries\BaseLibrary;
use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

class OSTicketLibrary extends BaseLibrary
{
    public static function send(string $method, string $endpoint, array $data = [], bool $official = false): false|Response
    {
        if ($official) {
            $client = self::constructClient([
                'headers' => [
                    'Accept' => 'application/json',
                    'X-API-Key' => config('osticket.token_official'),
                ],
            ]);
        } else {
            $client = self::constructClient([
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Token ' . config('osticket.token'),
                ],
            ]);
        }

        if ($official) {
            $uri = config('osticket.url_official') . '/' . $endpoint;
        } else {
            $uri = config('osticket.url') . '/' . $endpoint;
        }

        try {
            return $client->request($method, $uri, ['json' => $data]);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
            Log::info($e->getMessage());
            return false;
        }
    }

    public static function get_group_name(int $id): ?string
    {
        $result = self::send('GET', 'dept/getDepartments');
        if (!$result) {
            return null;
        }
        $result_data = json_decode($result->getBody()->getContents());
        foreach ($result_data?->departments as $d) {
            if ($d?->id == $id) {
                return $d?->name;
            }
        }
        return null;
    }

    public static function check_user(User $user): bool
    {
        $roles = $user->service_role_ids(ServiceRoleType::SupportGroup, cast_to_int: true);

        $result = self::send('POST', 'user/syncUserGroups', [
            'user_id' => strval($user->id),
            'dept_ids' => $roles,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
        ]);
        if (!$result) {
            return false;
        }
        $result_data = json_decode($result->getBody()->getContents());

        if ($result_data?->usercreated) {
            $user->notify(
                new BasicNotification(
                    'Dein Account im Ticketsystem',
                    "Es wurde ein Account für dich im Ticketsystem angelegt. Dein Loginname lautet:
                    <code>v$user->id</code>
                    mit der E-Mail: 
                    <code>$user->email</code>
                    und initialen Passwort:
                    <code>$result_data->password</code>
                    Du wirst nach dem ersten Login aufgefordert dein Passwort zu ändern.",
                    'Tech Leitung',
                    'hier gehts zum Agentenlogin',
                    'https://support.vatsim-germany.org/scp/login.php',
                    Carbon::now()->addDays(14),
                    Carbon::now()->addDays(365),
                ),
            );
        }
        return true;
    }

    public static function create_ticket(string $name, string $mail, string $subject, string $content, int $supporttype = 0, int $area = 0): bool
    {
        $result = self::send(
            'POST',
            'tickets.json',
            [
                'name' => $name,
                'email' => $mail,
                'subject' => $subject,
                'message' => $content,
                'topicId' => self::map_topic_id($supporttype, $area),
            ],
            true,
        );
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    private static function map_topic_id(int $supporttype, int $area): int
    {
        $topicId = match ($area) {
            1 => 16, // Tech
            2 => 23, // NAV
            3 => 22, // Event
            4 => 21, // ATD
            5 => 20, // PTD
            6 => 28, // PV
            7 => 29, // Dir
            default => 14,
        };
        return $topicId;
    }
}
