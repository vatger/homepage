<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class NextcloudLibrary extends BaseLibrary
{
    private static function send(string $method, string $endpoint, array $data = []): false|Response
    {
        $user = config('nextcloud.username');
        $pwd = config('nextcloud.password');
        $client = self::constructClient([
            'auth' => [$user, $pwd],
            'headers' => ['OCS-APIRequest' => 'true'],
        ]);

        $uri = config('nextcloud.url') . '/' . $endpoint;

        try {
            if (empty($data)) {
                return $client->request($method, $uri);
            } else {
                return $client->request($method, $uri, ['form_params' => $data]);
            }
        } catch (GuzzleException $e) {
            echo $e->getMessage();
            Log::info($e->getMessage());
            return false;
        }
    }

    //
    private static function mergeElementAboveLevel($data)
    {
        if (is_object($data) && empty(get_object_vars($data))) {
            return null;
        }
        if (!is_object($data)) {
            return $data;
        } else {
            foreach ($data as $key => $value) {
                if ($key == 'element' && is_array($data->$key)) {
                    return $value;
                } elseif ($key == 'element') {
                    return [$value];
                } else {
                    $data->$key = self::mergeElementAboveLevel($value);
                }
            }
        }
        return $data;
    }

    private static function sendAndDecode(string $method, string $endpoint, array $data = []): ?object
    {
        $response = self::send($method, $endpoint, $data);
        if (!$response) {
            return null;
        }
        $response_content = json_decode(json_encode(simplexml_load_string($response->getBody()->getContents())));
        $obj = (object) [
            'meta' => $response_content->meta,
            'data' => self::mergeElementAboveLevel($response_content->data),
        ];
        return $obj;
    }

    public static function get_all_groups(): array
    {
        $result = Cache::remember('NextcloudLibrary.get_all_groups', 60, fn() => self::sendAndDecode('GET', 'groups'));
        return $result?->data?->groups ?? [];
    }

    public static function get_group_name(string $id): ?string
    {
        $groups = self::get_all_groups();
        if (in_array($id, $groups)) {
            return 'ok';
        }
        return null;
    }

    public static function check_user(User $user): bool
    {
        // Neue Benutzergruppen ermitteln
        $newgroups = $user->service_role_ids(ServiceRoleType::NextcloudGroup);

        //Existiert User?
        $username = "$user->id";
        $result_data = self::sendAndDecode('GET', "users/$username");
        if (!$result_data->data) {
            if (empty($newgroups)) {
                return true;
            } else {
                $username = self::create_user($username, $user->email, "$user->firstname $user->lastname");
                $notification = new BasicNotification(
                    'Dein Account im DMS',
                    "Es wurde ein Account für dich im DMS angelegt. Dein Loginname lautet:
                    <code>$username</code>
                    mit der E-Mail: 
                    <code>$user->email</code>
                    .
                    Nutze die Funktion <i>Passwort vergessen</i> um dein Passwort zu ändern.",
                    'Tech Leitung',
                    'hier gehts zum Login',
                    'https://dms.vatsim-germany.org/login?clear=1',
                    Carbon::now()->addDays(14),
                    Carbon::now()->addDays(365),
                );
                $user->notify($notification);
            }
        }

        if (empty($newgroups)) {
            self::delete_user($username);
            return true;
        }

        self::sync_groups($newgroups, $username);
        return true;
    }

    private static function create_user(string $username, string $email, string $displayname): string
    {
        $userid = '';
        $result = self::sendAndDecode('POST', 'users', ['userid' => $username, 'password' => 'V' . Str::random() . '!']);
        if ($result->data->id) {
            $userid = $result->data->id;
            self::send('PUT', "users/$userid", ['key' => 'email', 'value' => $email]);
            self::send('PUT', "users/$userid", ['key' => 'displayname', 'value' => $displayname]);
        }
        return $userid;
    }

    private static function delete_user(string $username): void
    {
        $result = self::send('DELETE', "users/$username");
        if ($result) {
            Log::info("User deleted: $username");
        } else {
            Log::info("User deletion failed: $username");
        }
    }

    private static function sync_groups(array $newgroups, string $username): void
    {
        $result = self::sendAndDecode('GET', "users/$username/groups");
        $currentgroups = $result?->data?->groups ?? [];

        if (!empty($currentgroups)) {
            $to_delete = array_diff($currentgroups, $newgroups);
            $to_add = array_diff($newgroups, $currentgroups);
        } else {
            $to_delete = [];
            $to_add = $newgroups;
        }

        foreach ($to_delete as $groupdel) {
            $result = self::sendAndDecode('DELETE', "users/$username/groups", ['groupid' => $groupdel]);
            if ($result->meta->statuscode != 100) {
                Log::info("Error member $username could not be deleted from team $groupdel");
            }
        }

        foreach ($to_add as $groupadd) {
            $result = self::sendAndDecode('POST', "users/$username/groups", ['groupid' => $groupadd]);
            if ($result->meta->statuscode != 100) {
                Log::info("Error member $username could not be added to team $groupadd");
            }
        }
    }
}
