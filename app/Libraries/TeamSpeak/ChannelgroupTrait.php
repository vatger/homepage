<?php

namespace App\Libraries\TeamSpeak;

use Illuminate\Support\Facades\Cache;

trait ChannelgroupTrait
{
    /**
     * Get the channel group id of the first channel group with this name
     */
    private static function getChannelgroupId(string $name): int|false
    {
        return Cache::remember('teamspeak.channelgroupid.' . $name, 60, function () use ($name) {
            $list = self::_channelgrouplist();
            if (!$list) {
                return false;
            }
            foreach ($list as $group) {
                if (strcmp($group->name, $name) == 0 && strcmp($group->type, 1) == 0) {
                    return $group->cgid;
                }
            }
            return false;
        });
    }

    private static function _channelgrouplist(): array|false
    {
        return Cache::remember('teamspeak.channelgrouplist', 120, function () {
            return self::_sendWebQuery('channelgrouplist');
        });
    }

    private function _setclientchannelgroup(int $channelgroupid, int $channelid, int $clientdatabaseid): bool
    {
        return self::_sendWebQuery('setclientchannelgroup', [
            'cgid' => $channelgroupid,
            'cid' => $channelid,
            'cldbid' => $clientdatabaseid,
        ]);
    }
}
