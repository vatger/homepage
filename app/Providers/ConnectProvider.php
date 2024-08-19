<?php

namespace App\Providers;

use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token;

class ConnectProvider extends GenericProvider
{
    /**
     * The route where we will redirect to after connect sign-on
     */
    private string $_redirectAtferAuthentication = 'vatsim.authentication.connect.callback';


    /**
     * Initialize the Provider from configuration
     */
    function __construct()
    {
        parent::__construct([
            'clientId' => config('vatsim.authentication.connect.id'), // The client ID assigned to you by the provider
            'clientSecret' => config('vatsim.authentication.connect.secret'), // The client password assigned to you by the provider
            'redirectUri' => str_replace("www.", "", route($this->_redirectAtferAuthentication)),
            'urlAuthorize' => config('vatsim.authentication.connect.base') . '/oauth/authorize',
            'urlAccessToken' => config('vatsim.authentication.connect.base') . '/oauth/token',
            'urlResourceOwnerDetails' => config('vatsim.authentication.connect.base') . '/api/user',
            'scopes' => Str::contains(config('vatsim.authentication.connect.scopes'), ',')
                ? str_replace(',', ' ', config('vatsim.authentication.connect.scopes'))
                : config('vatsim.authentication.connect.scopes'),
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
