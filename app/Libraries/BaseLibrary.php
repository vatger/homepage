<?php

namespace App\Libraries;

use GuzzleHttp\Client;

class BaseLibrary
{
    public const SyncForum = 'forum';

    public const SyncTeamspeak = 'ts';

    public const SyncKnowledgebase = 'bookstack';

    public const SyncDMS = 'nextcloud';

    public const SyncVikunja = 'vikunja';

    public const SyncOSTicket = 'osticket';

    public const SyncMailcow = 'mailcow';

    public const SyncDiscord = 'discord';

    protected static function constructClient(array $config = []): Client
    {
        $config['headers'] = array_merge($config['headers'] ?? [], ['User-Agent' => 'VATGER/3']);
        // Keep unavailable integrations from blocking web requests. The
        // connection timeout is intentionally shorter than PHP's max
        // execution time, especially for local development environments.
        $config['timeout'] = $config['timeout'] ?? config('api_sync.http_timeout', 5);
        $config['connect_timeout'] = $config['connect_timeout'] ?? config('api_sync.http_connect_timeout', 2);
        $config['read_timeout'] = $config['read_timeout'] ?? config('api_sync.http_read_timeout', 5);

        return new Client($config);
    }

    public static function is_active(string $LibName): bool
    {
        $active = config("api_sync_active.$LibName");
        if ($active) {
            return true;
        } else {
            return false;
        }
    }
}
