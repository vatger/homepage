<?php

namespace App\Libraries\TeamSpeak;

use App\Models\Membership\TeamSpeak\Registration;
use Illuminate\Support\Facades\Cache;

trait ServergroupTrait
{
    /**
     * Get the server group id of the first server group with this name
     */
    public static function getServergroupId(string $name): int|false
    {
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
    }

    public static function getServergroupName(int $id): string|false
    {
        $list = self::_servergrouplist();
        if (!$list) {
            return false;
        }
        foreach ($list as $group) {
            if ($group->sgid == $id && strcmp($group->type, 1) == 0) {
                return $group->name;
            }
        }
        return false;
    }

    public static function addToServergroup(Registration $registration, int $id): bool
    {
        $clientdbid = $registration->dbid;
        $serverGroupId = $id;
        return self::_servergroupaddclient($clientdbid, $serverGroupId);
    }

    public static function delFromServergroup(Registration $registration, int $id): bool
    {
        $clientdbid = $registration->dbid;
        $serverGroupId = $id;
        return self::_servergroupdelclient($clientdbid, $serverGroupId);
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
