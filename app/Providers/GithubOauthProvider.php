<?php

namespace App\Providers;

use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\GenericProvider;

class GithubOauthProvider extends GenericProvider
{
    /**
     * Initialize the Provider from configuration
     */
    function __construct()
    {
        parent::__construct([
            'clientId' => config('github.oauth.id'),
            'clientSecret' => config('github.oauth.secret'),
            'redirectUri' => str_replace("www.", "", route('github.oauth.callback')),
            'urlAuthorize' => config('github.oauth.authorize'),
            'urlAccessToken' => config('github.oauth.token'),
            'urlResourceOwnerDetails' => config('github.oauth.user'),
            'scopes' => str_replace(',', ' ', config('github.oauth.scopes')),
            'scopeSeparator' => ' ',
        ]);
    }
}
