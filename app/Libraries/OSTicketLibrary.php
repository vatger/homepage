<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User;
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
                    'Authorization' => 'Token '.config('osticket.token'),
                ],
            ]);
        }

        if ($official) {
            $uri = config('osticket.url_official').'/'.$endpoint;
        } else {
            $uri = config('osticket.url').'/'.$endpoint;
        }

        try {
            return $client->request($method, $uri, ['json' => $data]);
        } catch (GuzzleException $e) {
            Log::info($e->getMessage());

            return false;
        }
    }

    public static function get_group_name(int $id): ?string
    {
        $result = self::send('GET', 'dept/getDepartments');
        if (! $result) {
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
        if (! $result) {
            return false;
        }
        $result_data = json_decode($result->getBody()->getContents());

        if ($result_data && property_exists($result_data, 'usercreated') && $result_data->usercreated) {
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

    public static function create_ticket(string $name, string $mail, string $subject, string $content, int $topic_id): bool
    {
        $result = self::send(
            'POST',
            'tickets.json',
            [
                'name' => $name,
                'email' => $mail,
                'subject' => $subject,
                'message' => $content,
                'topicId' => $topic_id,
            ],
            true,
        );
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
