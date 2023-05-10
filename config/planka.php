<?php

/**
 * Configuration for the Planka API.
 */
return [
    'url' => env('PLANKA_URL', 'https://planka.vatsim-germany.org/api/'),
    'user' => env('PLANKA_USERNAME', 'webmaster@vatsim-germany.org'),
    'password' => env('PLANKA_PASSWORD', '123'),
];
