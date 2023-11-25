<?php

/**
 * Configuration for the XenBridge Forum API.
 */
return [
    'url' => env('FORUM_URL', 'https://board.vatsim-germany.org'),
    'apikey' => env('FORUM_API_KEY', ''),
    'newsId' => env('FORUM_NEWS_THREAD', 97),
    'baseGroup' => env('FORUM_BASE_GROUP', 2), // 2 is the standard registered group id by default
    'guestGroup' => env('FORUM_GUEST_GROUP', 55), // if a user has no secondary group he gets this group
    'bannedGroup' => env('FORUM_BANNED_GROUP', null),
    'inactiveGroup' => env('FORUM_INACTIVE_GROUP', null),
];
