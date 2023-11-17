<?php

namespace App\Libraries\TeamSpeak;

use App\Models\Groups\ServiceRole;
use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\TeamspeakRegistration;
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

    public static function getServergroupName(int $id): ?string
    {
        $list = self::_servergrouplist();
        if (!$list) {
            return null;
        }
        foreach ($list as $group) {
            if ($group->sgid == $id && strcmp($group->type, 1) == 0) {
                return $group->name;
            }
        }
        return null;
    }

    public static function addToServergroup(TeamspeakRegistration $registration, int $id): bool
    {
        $clientdbid = $registration->dbid;
        $serverGroupId = $id;
        return self::_servergroupaddclient($clientdbid, $serverGroupId);
    }

    public static function delFromServergroup(TeamspeakRegistration $registration, int $id): bool
    {
        $clientdbid = $registration->dbid;
        $serverGroupId = $id;
        return self::_servergroupdelclient($clientdbid, $serverGroupId);
    }

    public static function listAvailServiceRoleId(): array
    {
        return ServiceRole::query()
            ->where('service_type', 'LIKE', ServiceRoleType::TeamspeakServergroup)
            ->select('service_role')
            ->get()
            ->map(fn($sr) => intval($sr->service_role))
            ->toArray();
    }

    public static function listServerGroupIds(bool $with_standard_groups = true, bool $only_webside_groups = false): array|false
    {
        $list = self::_servergrouplist();
        if (!$list) {
            return false;
        }
        $groups = [];
        foreach ($list as $group) {
            if (!$with_standard_groups && $group->sgid == self::getServergroupId(config('teamspeak.default_group'))) {
                continue;
            }
            if (strcmp($group->type, 1) == 0) {
                $groups[] = $group->sgid;
            }
        }
        return $groups;
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
