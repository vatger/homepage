<?php

namespace App\Providers;

use League\OAuth2\Client\Provider\GenericProvider;

class ConnectProvider extends GenericProvider
{
    /**
     * Initialize the Provider from configuration
     */
    public function __construct()
    {
        parent::__construct([
            'clientId' => config('vatsim.authentication.connect.id'),
            'clientSecret' => config('vatsim.authentication.connect.secret'),
            'redirectUri' => str_replace('www.', '', route('vatsim.authentication.connect.callback')),
            'urlAuthorize' => config('vatsim.authentication.connect.base').'/oauth/authorize',
            'urlAccessToken' => config('vatsim.authentication.connect.base').'/oauth/token',
            'urlResourceOwnerDetails' => config('vatsim.authentication.connect.base').'/api/user',
            'scopes' => str_replace(',', ' ', config('vatsim.authentication.connect.scopes')),
            'scopeSeparator' => ' ',
        ]);
    }
}
