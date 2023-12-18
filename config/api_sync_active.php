<?php
return [
    'forum' => env('ACTIVE_SYNC_FORUM', false),
    'ts' => env('ACTIVE_SYNC_TS', false),
    'bookstack' => env('ACTIVE_SYNC_BOOKSTACK', false),
    'nextcloud' => env('ACTIVE_SYNC_NEXTCLOUD', false),
    'vikunja' => env('ACTIVE_SYNC_VIKUNJA', false),
    'osticket' => env('ACTIVE_SYNC_OSTICKET', false),
    'mailcow' => env('ACTIVE_SYNC_MAILCOW', false),
    'sdp_enforce' => env('SDP_ENFORCE', false),
];
