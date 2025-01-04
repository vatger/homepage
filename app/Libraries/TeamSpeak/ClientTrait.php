<?php

namespace App\Libraries\TeamSpeak;

use Illuminate\Support\Facades\Cache;

trait ClientTrait
{
    /**
     * Get the clients from teamspeak database
     * starting at offset $startNr
     */
    public static function getClientDB(int $startNr = 0): array
    {
        $clients = self::_clientdblist($startNr);
        if ($clients == false) {
            return [];
        }

        return $clients;
    }

    private static function _clientdbedit(int $clientDBid, string $client_description = ''): bool
    {
        return self::_sendWebQuery('clientdbedit', [
            'cldbid' => $clientDBid,
            'client_description' => $client_description,
        ]);
    }

    private static function _clientdblist(int $start = 0): mixed
    {
        return Cache::remember('teamspeak.clientdblist.'.$start, 30, function () use ($start) {
            return self::_sendWebQuery('clientdblist', ['start' => $start]);
        });
    }

    private static function _clientgetdbidfromuid(string $uid): mixed
    {
        return self::_sendWebQuery('clientgetdbidfromuid', ['cluid' => $uid]);
    }
}
