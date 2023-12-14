<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
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

    public static function check_user(User $user): bool
    {
        // Neue Benutzergruppen ermitteln
        $newgroups = $user->service_role_ids(ServiceRoleType::NextcloudGroup);

        //Existiert User?
        $username = "$user->id";
        $result = self::send('GET', "users/$username");
        $result_data = json_decode(json_encode(simplexml_load_string($result->getBody()->getContents())));

        if (!$result_data->data->id) {
            if (empty($newgroups)) {
                return true;
            } else {
                $username = self::create_user($username, $user->email, "$user->firstname $user->lastname");
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
        $result = self::send('POST', 'users', ['userid' => $username, 'password' => 'V' . Str::random() . '!']);
        $result_data = json_decode(json_encode(simplexml_load_string($result->getBody()->getContents())));
        if ($result_data->data->id) {
            $userid = $result_data->data->id;
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
            Log::info("User deleteion failed: $username");
        }
    }

    private static function sync_groups(array $newgroups, string $username): void
    {
        $result = self::send('GET', "users/$username/groups");
        $result_data = json_decode(json_encode(simplexml_load_string($result->getBody()->getContents())));
        $currentgroups = $result_data->groups;

        if (!empty($currentgroups)) {
            $to_delete = array_diff($currentgroups, $newgroups);
            $to_add = array_diff($newgroups, $currentgroups);
        } else {
            $to_add = $newgroups;
        }

        foreach ($to_delete as $groupdel) {
            $result = self::send('DELETE', "users/$username/groups", ['key' => 'groupid', 'value' => $groupdel]);
            if ($result->getStatusCode() != 100) {
                Log::info("Error member $username could not be deleted from team $groupdel");
            }
        }

        foreach ($to_add as $groupadd) {
            $result = self::send('POST', "users/$username/groups", ['key' => 'groupid', 'value' => $groupadd]);
            if ($result->getStatusCode() != 100) {
                Log::info("Error member $username could not be added to team $groupadd");
            }
        }
    }
}
