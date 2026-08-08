<?php

return [
    'forum' => env('ACTIVE_SYNC_FORUM', false),
    'ts' => env('ACTIVE_SYNC_TS', false),
    'bookstack' => env('ACTIVE_SYNC_BOOKSTACK', false),
    'nextcloud' => env('ACTIVE_SYNC_NEXTCLOUD', false),
    'vikunja' => env('ACTIVE_SYNC_VIKUNJA', false),
    'osticket' => env('ACTIVE_SYNC_OSTICKET', false),
    'mailcow' => env('ACTIVE_SYNC_MAILCOW', false),
    'discord' => env('ACTIVE_SYNC_DISCORD', false),
    'sdp_enforce' => env('SDP_ENFORCE', false),
    'http_timeout' => env('API_SYNC_HTTP_TIMEOUT', 5),
    'http_connect_timeout' => env('API_SYNC_HTTP_CONNECT_TIMEOUT', 2),
    'http_read_timeout' => env('API_SYNC_HTTP_READ_TIMEOUT', 5),
];
