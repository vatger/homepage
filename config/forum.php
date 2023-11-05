<?php

/**
 * Configuration for the XenBridge Forum API.
 */
return [
    'url' => env('FORUM_URL', 'https://board.vatsim-germany.org'),
    'apikey' => env('FORUM_API_KEY', ''),
    'newsId' => env('FORUM_NEWS_THREAD', 97),
    'baseGroup' => env('FORUM_BASE_GROUP', 2), // 2 is the standard registered group id by default
    'memberGroup' => env('FORUM_MEMBER_GROUP', null), // if a user is full member
    'guestGroup' => env('FORUM_GUEST_GROUP', 55), // if a user has no secondary group he gets this group
    'banedGroup' => env('FORUM_BANNED_GROUP', null),
    'inactiveGroup' => env('FORUM_INACTIVE_GROUP', null),
];
