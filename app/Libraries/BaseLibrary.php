<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BaseLibrary
{
    protected static function constructClient(array $config = []): Client
    {
        $config['headers'] = array_merge($config['headers'] ?? [], ['User-Agent' => 'VATGER/3']);
        $config['connect_timeout'] = $config['connect_timeout'] ?? 30;
        $config['read_timeout'] = $config['read_timeout'] ?? 30;
        $config['timeout'] = $config['timeout'] ?? 45;

        return new Client($config);
    }
}
