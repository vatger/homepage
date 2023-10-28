<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BaseLibrary
{
    protected static function constructClient(array $config = []): Client
    {
        $config['headers'] = array_merge($config['headers'] ?? [], ['User-Agent' => 'VATGER/3']);
        $config['connect_timeout'] = $config['connect_timeout'] ?? 15;
        $config['read_timeout'] = $config['read_timeout'] ?? 15;
        $config['timeout'] = $config['timeout'] ?? 15;

        return new Client($config);
    }
}
