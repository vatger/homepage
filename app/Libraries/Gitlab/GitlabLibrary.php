<?php

namespace App\Libraries\Gitlab;

use App\Models\Membership\User\User;
use App\Models\Regionalgroup_remove\Regionalgroup;
use GuzzleHttp\Client;

class GitlabLibrary
{
    private static Client $client;

    private static function _setup()
    {
        $uri = config('gitlab.url'); //https://gitlab.example.com/api/v4
        $access_token = config('gitlab.apikey');

        self::$client = new Client([
            'base_uri' => $uri,
            'connect_timeout' => 30,
            'read_timeout' => 30,
            'timeout' => 30,
            'headers' => [
                'Authorization' => "Bearer {$access_token}",
            ],
        ]);
    }

    public static function createAccount(User $user): bool
    {
        self::_setup();
        if ($user->settings->gitlab_id != null) {
            return false;
        } //already has gitlab account
        $res = self::$client->request('POST', 'users', [
            'form_params' => [
                'username' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'reset_password' => true,
            ],
        ]);
        if ($res->getStatusCode() < 200 || $res->getStatusCode() > 299) {
            return false;
        }
        $json = json_decode($res->getBody()->getContents());
        /*$createUserResponse = '{"id":75,"username":"10000001","name":"Web One","state":"active","avatar_url":"https://secure.gravatar.com/avatar/5bf979420dd45b7c9b1ad403fe051cae?s=80\u0026d=identicon","web_url":"https://git.vatsim-germany.org/10000001","created_at":"2022-03-16T12:55:35.202Z","bio":"","location":null,"public_email":null,"skype":"","linkedin":"","twitter":"","website_url":"","organization":null,"job_title":"","pronouns":null,"bot":false,"work_information":null,"followers":0,"following":0,"local_time":null,"last_sign_in_at":null,"confirmed_at":null,"last_activity_on":null,"email":"auth.dev1@vatsim.net","theme_id":1,"color_scheme_id":1,"projects_limit":100,"current_sign_in_at":null,"identities":[],"can_create_group":true,"can_create_project":true,"two_factor_enabled":false,"external":false,"private_profile":false,"commit_email":"auth.dev1@vatsim.net","is_admin":false,"note":null}';*/
        $user->settings->gitlab_id = $json->id;
        $user->settings->save();
        return true;
    }

    public static function checkNAVAssignments(User $user): void
    {
        self::_setup();
        if ($user->settings->gitlab_id == null) {
            return;
        } //user has no account
        //now this will not be good, but it will work until V3 :)
        $rgs = Regionalgroup::all();
        $mapping = [
            2 => 92, //edbb
            1 => 93, //edww
            3 => 94, //edll
            4 => 95, //edff
            5 => 96, //edmm
        ];
        foreach ($rgs as $rg) {
            $rg_gitlab_group = $mapping[$rg->id];
            if (
                $rg
                    ->navigators()
                    ->wherePivot('user_id', $user->id)
                    ->count()
            ) {
                try {
                    self::assignToGroup($user, $rg_gitlab_group);
                } catch (\Exception $e) {
                }
            } else {
                try {
                    self::removeFromGroup($user, $rg_gitlab_group);
                } catch (\Exception $e) {
                }
            }
        }
    }

    private static function assignToGroup(User $user, int $gitlab_group_id, int $gitlab_access_level = 30): bool
    {
        if ($user->settings->gitlab_id == null) {
            return false;
        } //has no gitlab account
        $res = self::$client->request('POST', "groups/{$gitlab_group_id}/members", [
            'form_params' => [
                'user_id' => $user->settings->gitlab_id,
                'access_level' => $gitlab_access_level,
            ],
        ]);
        if ($res->getStatusCode() < 200 || $res->getStatusCode() > 299) {
            return false;
        }
        return true;
    }

    public function removeFromGroup(User $user, int $gitlab_group_id): bool
    {
        if ($user->settings->gitlab_id == null) {
            return false;
        } //has no gitlab account
        $res = self::$client->request('DELETE', "groups/{$gitlab_group_id}/members/{$user->settings->gitlab_id}");
        if ($res->getStatusCode() < 200 || $res->getStatusCode() > 299) {
            return false;
        }
        return true;
    }
}
