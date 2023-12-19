<?php
/**
 * Configuration for the Bookstack API.
 */
return [
    'host' => rtrim(env('BOOKSTACK_HOST', 'http://knowledgebase.vatsim-germany.org'), '/'),
    'token_id' => env('BOOKSTACK_TOKEN_ID', ''),
    'token_secret' => env('BOOKSTACK_TOKEN_SECRET', ''),
    'public_role' => env('BOOKSTACK_PUBLIC_ROLE', 4),
];
