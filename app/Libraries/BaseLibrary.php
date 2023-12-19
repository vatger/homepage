<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BaseLibrary
{
    public const SyncForum = 'forum';
    public const SyncTeamspeak = 'ts';
    public const SyncKnowledgebase = 'bookstack';
    public const SyncDMS = 'nextcloud';
    public const SyncVikunja = 'vikunja';
    public const SyncOSTicket = 'osticket';
    public const SyncMailcow = 'mailcow';

    protected static function constructClient(array $config = []): Client
    {
        $config['headers'] = array_merge($config['headers'] ?? [], ['User-Agent' => 'VATGER/3']);
        $config['connect_timeout'] = $config['connect_timeout'] ?? 15;
        $config['read_timeout'] = $config['read_timeout'] ?? 15;
        $config['timeout'] = $config['timeout'] ?? 15;

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
