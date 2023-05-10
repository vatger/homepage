<?php

namespace App\Libraries\TeamSpeak;

use Illuminate\Support\Facades\Cache;

trait ServergroupTrait
{
    /**
     * Get the server group id of the first server group with this name
     */
    private static function getServergroupId(string $name): int|false
    {
        return Cache::remember('teamspeak.servergroupid.' . $name, 60, function () use ($name) {
            $list = self::_servergrouplist();
            if (!$list) {
                return false;
            }
            foreach ($list as $group) {
                if (strcmp($group->name, $name) == 0 && strcmp($group->type, 1) == 0) {
                    return $group->sgid;
                }
            }
            return false;
        });
    }

    private static function _servergroupaddclient(int $clientDBid, int $servergroupId): bool
    {
        return self::_sendWebQuery('servergroupaddclient', [
            'cldbid' => $clientDBid,
            'sgid' => $servergroupId,
        ]);
    }

    private static function _servergrouplist(): array|false
    {
        return Cache::remember('teamspeak.servergrouplist', 120, function () {
            return self::_sendWebQuery('servergrouplist');
        });
    }

    private static function _servergroupdelclient(int $clientDBid, int $servergroupId): bool
    {
        return self::_sendWebQuery('servergroupdelclient', [
            'cldbid' => $clientDBid,
            'sgid' => $servergroupId,
        ]);
    }
}
