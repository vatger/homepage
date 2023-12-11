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
        $username = substr($user->firstname, 0, 1) . '.' . $user->lastname;
        $username = strtolower($username . '2');
        $result = self::send('GET', "users/$username");
        $result_data = json_decode(json_encode(simplexml_load_string($result->getBody()->getContents())));

        if (!$result_data->data->id) {
            $username = self::create_user($username, $user->email, "$user->firstname $user->lastname");
            if (empty($newgroups)) {
                return true;
            } else {
                $username = self::create_user($username, $user->email, "$user->firstname $user->lastname");
            }
        }
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
}
