<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

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
                return $client->request($method, $uri, ['json' => $data]);
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
        $username = "substr($user->firstname,0,1).$user->lastname";
        $result = self::send('GET', "users?search=$username");
        $result_data = json_decode(json_encode(simplexml_load_string($result)));
        var_dump($result_data);
        return true;
    }
}
