<?php
return [
    'oauth' => [
        'id' => env("GITHUB_OAUTH_ID"),
        'secret' => env("GITHUB_OAUTH_SECRET"),
        'authorize' => 'https://github.com/login/oauth/authorize',
        'token' => 'https://github.com/login/oauth/access_token',
        'user' => 'https://api.github.com/user',
        'scopes' => 'user'

    ]

];
