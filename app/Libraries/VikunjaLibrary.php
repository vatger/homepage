<?php

namespace App\Libraries;
use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;

class VikunjaLibrary extends BaseLibrary
{
    private $jwt_token;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->jwt_token = $this->login(config('vikunja.username'), config('vikunja.password'));
        if (empty($this->jwt_token)) {
            throw new \Exception('Login to vikunja instance failed');
        }
        Log::info("Login successful: $this->jwt_token");
    }

    private function send(string $method, string $endpoint, array $data = []): false|Response
    {
        $client = self::constructClient([
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->jwt_token,
            ],
        ]);

        $uri = config('vikunja.url') . '/' . $endpoint;

        try {
            if (empty($data)) {
                return $client->request($method, $uri);
            } else {
                return $client->request($method, $uri, ['json' => $data]);
            }
        } catch (GuzzleException $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    public function check_user(User $user): bool
    {
        $new_teams = $user->service_role_ids(ServiceRoleType::VikunjaGroup, cast_to_int: true);
        $result = $this->send('GET', "user?s=$user->id");
        $result_data = json_decode($result->getBody()->getContents());

        if ($result_data == null) {
            // no user exists
            if (!$new_teams) {
                // no roles should be assigned => everything's fine
                return true;
            } else {
                // else create new user
                //Create user
                if (!$this->create_user($user)) {
                    return false;
                }
            }
        }

        // Read all teams
        $result = $this->send('GET', 'teams');
        $result_data = json_decode($result->getBody()->getContents());

        foreach ($result_data as $team) {
            foreach ($team->members as $member) {
                if ($member->username == $user->id) {
                    $userid = $user->id;
                    $old_teams[] = $team->id;
                }
            }
        }

        if (!empty($old_teams)) {
            $to_delete = array_diff($old_teams, $new_teams);
            $to_add = array_diff($new_teams, $old_teams);
        } else {
            $to_add = $new_teams;
        }

        var_dump($to_delete);
        foreach ($to_delete as $teamdel) {
            var_dump($teamdel);
            $result = $this->send('DELETE', "teams/$teamdel/members/$userid");

            if ($result->getStatusCode() != 200) {
                Log::info("Error member $user->id could not be deleted from team $teamdel");
            }
        }

        var_dump($to_add);
        foreach ($to_add as $teamadd) {
            var_dump($teamadd);
            $result = $this->send('PUT', "teams/$teamadd/members", ['admin' => false, 'created' => '', 'id' => 0, 'username' => $user->id]);
            if ($result->getStatusCode() != 201) {
                Log::info("Error member $user->id could not be added to team $teamdel");
            }
        }
        return true;
    }

    private function create_user(User $user): bool
    {
        $jwt_save = $this->jwt_token;
        $pwd = Str::random();

        $result = $this->send('POST', 'register', ['email' => $user->email, 'username' => $user->id, 'password' => $pwd]);

        $user->notify(
            new BasicNotification(
                'Dein Account im Ticketsystem',
                "Es wurde ein Account für dich im Vikunja angelegt. Dein Loginname lautet:
                    <code>v$user->id</code>
                    mit der Email: 
                    <code>$user->email</code>
                    Bitte verwende die Passwort vergessen Funktion um dein Passwort für den erstmaligen Login zu setzen",
                'Tech Leitung',
                'hier gehts zum Login',
                'https://vikunja.vatsim-germany.org/',
                Carbon::now()->addDays(14),
                Carbon::now()->addDays(365),
            ),
        );

        $this->jwt_token = $this->login($user->id, $pwd);
        if ($this->jwt_token . isEmptyOrNullString()) {
            $this->jwt_token = $jwt_save;
            return false;
        }
        $result = $this->send('POST', 'user/settings/general', [
            'default_project_id' => 0,
            'discoverable_by_email' => true,
            'discoverable_by_name' => true,
            'email_reminders_enabled' => true,
            'frontend_settings' => null,
            'language' => 'en',
            'name' => "$user->firstname $user->lastname",
            'overdue_tasks_reminders_enabled' => true,
            'overdue_tasks_reminders_time' => '09:00',
            'timezone' => 'Europe/Berlin',
            'week_start' => 1,
        ]);
        if ($result->getStatusCode() != 200) {
            Log::info("Error updating vikunja username for $user->id");
        }
        $this->jwt_token = $jwt_save;
        return true;
    }

    private function login(string $username, string $pwd): string
    {
        $result = $this->send('POST', 'login', ['long_token' => true, 'username' => $username, 'password' => $pwd]);

        $result_data = json_decode($result->getBody()->getContents());
        return $result_data?->token;
    }
    private function get_groups(): array
    {
        $result = $this->send('GET', 'teams');
        $result_data = json_decode($result->getBody()->getContents());

        foreach ($result_data as $team) {
            $teams[] = (object) ['team' => $team->id, 'name' => $team->name];
        }
        return $teams;
    }

    public static function get_group_name(int $id): ?string
    {
        $teams = Cache::remember('VikunjaLibrary.Teams', 120, fn() => (new self())->get_groups());

        foreach ($teams as $team) {
            if ($team->team == $id) {
                return $team->name;
            }
        }
        return null;
    }
}
