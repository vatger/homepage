<?php

namespace App\Libraries\TeamSpeak;

use Illuminate\Support\Facades\Cache;

trait BanTrait
{
    private static function _banlist(): mixed
    {
        return Cache::remember('teamspeak.banlist', 120, function () {
            return self::_sendWebQuery('banlist');
        });
    }

    private static function _banadd(string $uid, int $time, string $text = 'Banned!'): bool
    {
        Cache::forget('teamspeak.banlist');
        return self::_sendWebQuery('banadd', [
            'uid' => $uid,
            'time' => $time,
            'text' => $text,
        ]);
    }

    private static function _bandel(int $banID): bool
    {
        Cache::forget('teamspeak.banlist');
        return self::_sendWebQuery('bandel', [
            'banid' => $banID,
        ]);
    }
}
