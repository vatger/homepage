<?php

return [
    'user' => env('TS_USER', 'serveradmin'),
    'pass' => env('TS_PASS', ''),
    'host' => env('TS_HOST', '127.0.0.1'),
    'query_port' => env('TS_QUERY_PORT', 10011),
    'port' => env('TS_PORT', 9987),
    'default_group' => env('TS_NEW_GROUP', 'Normal'),
    'apikey' => env('TS_APIKEY', ''),
    'webquery_port' => env('TS_WEBQUERY_PORT', 10080),
    'server_number' => env('TS_SERVER_NR', 1),
];
