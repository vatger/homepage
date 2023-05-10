<?php

namespace App\Providers\VATSIM;

use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token;

class ConnectProvider extends GenericProvider
{
    /**
     * The route where we will redirect to after connect sign-on
     */
    private string $_redirectAtferAuthentication = 'vatsim.authentication.connect.login';

    /**
     * Force required scopes
     */
    private bool $_useRequiredScopes = true;

    /**
     * Initialize the Provider from configuration
     */
    function __construct()
    {
        parent::__construct([
            'clientId' => config('vatsim.authentication.connect.id'), // The client ID assigned to you by the provider
            'clientSecret' => config('vatsim.authentication.connect.secret'), // The client password assigned to you by the provider
            'redirectUri' => route($this->_redirectAtferAuthentication),
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
     * OVERWRITTEN
     * Returns authorization parameters based on provided options.
     *
     * @param array $options
     * @return string Authorization URL
     */
    public function getAuthorizationUrl(array $options = []): string
    {
        $base = $this->getBaseAuthorizationUrl();

        // injects getDefaultScopes in the initial redirect url as required_scopes
        if ($this->_useRequiredScopes) {
            if (empty($options['required_scopes'])) {
                $options['required_scopes'] = $this->getDefaultScopes();
            }
            if (is_array($options['required_scopes'])) {
                $separator = $this->getScopeSeparator();
                $options['required_scopes'] = implode($separator, $options['required_scopes']);
            }
        }
        // end

        $params = $this->getAuthorizationParameters($options);
        $query = $this->getAuthorizationQuery($params);

        return $this->appendQuery($base, $query);
    }

    /**
     * Get a new token from an older one
     *
     * @return Token\AccessToken|Token\AccessTokenInterface
     */
    public static function updateToken($token)
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
