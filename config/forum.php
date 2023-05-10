<?php

/**
 * Configuration for the XenBridge Forum API.
 */
return [
    'url' => env('FORUM_URL', 'https://board.vatsim-germany.org'),
    'apikey' => env('FORUM_API_KEY', ''),
    'newsId' => env('FORUM_NEWS_THREAD', 97),
    'defaultGroup' => env('FORUM_DEFAULT_GROUP', 2), // 2 is the standard registered group id by default
    'suspendedGroup' => env('FORUM_SUSPENDED_GROUP', 56), // is the suspended group
    'guestGroup' => env('FORUM_GUEST_GROUP', 55), // if a user has no secondary group he gets this group
];
