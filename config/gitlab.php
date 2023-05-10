<?php

/**
 * Configuration for the Gitlab API.
 */
return [
    'url' => env('GITLAB_URL', 'https://git.vatsim-germany.org/api/v4/'),
    'apikey' => env('GITLAB_APIKEY', ''),
];
