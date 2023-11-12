<?php

namespace App\Libraries\TeamSpeak;

use App\Models\Membership\TeamspeakRegistration;
use Illuminate\Support\Facades\Cache;

trait BanTrait
{
    protected static function getBansFromRegistration(TeamspeakRegistration $registration): array
    {
        $allbans = self::_banlist();
        $registrationbans = [];
        if (!$allbans) {
            return $registrationbans;
        }

        foreach ($allbans as $ban) {
            if (strcmp($ban->uid, $registration->uid) == 0) {
                $registrationbans[] = $ban;
            }
        }
        return $registrationbans;
    }

    private static function _banlist(): mixed
    {
        return Cache::remember('teamspeak.banlist', 120, function () {
            return self::_sendWebQuery('banlist');
        });
    }

    private static function _banadd(string $uid, int $time, string $text = 'Banned!'): mixed
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
