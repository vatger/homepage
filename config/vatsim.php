<?php

return [
    'authentication' => [
        'connect' => [
            'base' => env('VATSIM_OAUTH_BASE', 'http://auth-dev.vatsim.net'),
            'id' => env('VATSIM_OAUTH_CLIENT', 0),
            'secret' => env('VATSIM_OAUTH_SECRET', ''),
            'scopes' => env('VATSIM_OAUTH_SCOPES', 'full_name,email,vatsim_details,country'),
        ],
    ],
    'api' => [
        'testing' => env('VATSIM_API_TESTING', false),
        'base' => env('VATSIM_API_BASE', 'https://api.vatsim.net/api'),
        'token' => env('VATSIM_API_TOKEN'),
    ],
    'booking' => [
        'base' => env('VATSIM_BOOKING_API_BASE', 'https://api.vatsim.net/api'),
        'token' => env('VATSIM_BOOKING_API_TOKEN'),
    ],
];
