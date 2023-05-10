<?php

namespace App\Libraries\TeamSpeak;

use Illuminate\Support\Facades\Cache;

trait PrivilegekeyTrait
{
    private static function _privilegekeyadd(
        int $tokentype,
        int $groupID,
        int $channelID,
        string $description = '',
        array $tokencustomset = [],
    ): mixed {
        Cache::forget('teamspeak.privilegekeylist');
        return self::_sendWebQuery(
            'privilegekeyadd',
            ['tokentype' => $tokentype],
            ['tokenid1' => $groupID],
            ['tokenid2' => $channelID],
            ['tokendescription' => $description],
            ['tokencustomset' => $tokencustomset],
        );
    }

    private static function _privilegekeydelete(string $token): bool
    {
        Cache::forget('teamspeak.privilegekeylist');
        return self::_sendWebQuery('privilegekeydelete', ['token' => $token]);
    }

    private static function _privilegekeylist(): mixed
    {
        return Cache::remember('teamspeak.privilegekeylist', 120, function () {
            return self::_sendWebQuery('privilegekeylist');
        });
    }
}
