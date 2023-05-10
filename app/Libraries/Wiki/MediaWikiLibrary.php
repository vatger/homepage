<?php

namespace App\Libraries\Wiki;

/*
 * Pls dont use this :)
 * @deprecated
 */

class MediaWikiLibrary
{
    private static $endPoint = 'https://de.wiki.vatsim-germany.org/api.php';

    public static function load()
    {
        $login_Token = self::getLoginToken(); // Step 1
        self::loginRequest($login_Token); // Step 2
        $userrights_Token = self::getUserRightsToken(); // Step 3
        self::change_userrights($userrights_Token); // Step 4
    }

    // Step 1: GET request to fetch login token
    public static function getLoginToken()
    {
        $params1 = [
            'action' => 'query',
            'meta' => 'tokens',
            'type' => 'login',
            'format' => 'json',
        ];

        $url = self::$endPoint . '?' . http_build_query($params1);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output, true);

        return $result['query']['tokens']['logintoken'];
    }

    // Step 2: POST request to log in. Use of main account for login is not
    // supported. Obtain credentials via Special:BotPasswords
    // (https://www.mediawiki.org/wiki/Special:BotPasswords) for lgname & lgpassword
    public static function loginRequest($logintoken)
    {
        $params2 = [
            'action' => 'login',
            'lgname' => 'VATSIM Germany@homepage',
            'lgpassword' => '6042n5bilo8jbbefnds3tpt3102c4kps',
            'lgtoken' => $logintoken,
            'format' => 'json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params2));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

        $output = curl_exec($ch);
        curl_close($ch);

        // echo "loginRequest";
        return $output;
    }

    // Step 3: GET request to fetch userrights token
    public static function getUserRightsToken()
    {
        $params3 = [
            'action' => 'query',
            'meta' => 'tokens',
            'type' => 'userrights',
            'format' => 'json',
        ];

        $url = self::$endPoint . '?' . http_build_query($params3);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output, true);
        return $result['query']['tokens']['userrightstoken'];
    }

    // Step 4: POST request to add or remove a user from a group
    public static function change_userrights($userrightstoken)
    {
        $params4 = [
            'action' => 'userrights',
            'user' => '1289607',
            'add' => 'Sichter',
            'expiry' => 'infinite',
            'reason' => 'API Testing',
            'token' => $userrightstoken,
            'format' => 'json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params4));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
