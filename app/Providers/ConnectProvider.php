<?php

namespace App\Providers;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token;

class ConnectProvider extends GenericProvider
{
    /**
     * Initialize the Provider from configuration
     */
    function __construct()
    {
        parent::__construct([
            'clientId' => config('vatsim.authentication.connect.id'),
            'clientSecret' => config('vatsim.authentication.connect.secret'),
            'redirectUri' => str_replace("www.", "", route('vatsim.authentication.connect.callback')),
            'urlAuthorize' => config('vatsim.authentication.connect.base') . '/oauth/authorize',
            'urlAccessToken' => config('vatsim.authentication.connect.base') . '/oauth/token',
            'urlResourceOwnerDetails' => config('vatsim.authentication.connect.base') . '/api/user',
            'scopes' => str_replace(',', ' ', config('vatsim.authentication.connect.scopes')),
            'scopeSeparator' => ' ',
        ]);
    }

    /**
     * Get a new token from an older one
     */
    public static function updateToken($token): Token\AccessTokenInterface|Token\AccessToken|null
    {
        $c = new ConnectProvider();

        try {
            return $c->getAccessToken('refresh_token', [
                'refresh_token' => $token->getRefreshToken(),
            ]);
        } catch (IdentityProviderException $e) {
            return null;
        }
    }
}
