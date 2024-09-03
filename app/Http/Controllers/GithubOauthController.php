<?php

namespace App\Http\Controllers;

use App\Providers\GithubOauthProvider;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GithubOauthController extends Controller
{
    protected GithubOauthProvider $provider;
    private string $state_session_key = 'github.oauth.state';

    function __construct()
    {
        parent::__construct();
        $this->provider = new GithubOauthProvider();
    }


    public function link(Request $request)
    {
        $authenticationUrl = $this->provider->getAuthorizationUrl();
        $request->session()->put($this->state_session_key, $this->provider->getState());
        return redirect()->away($authenticationUrl);
    }

    public function callback(Request $request)
    {
        if ($request->input('state') != session()->pull($this->state_session_key)) {
            return redirect()->route('github.oauth.link');
        }
        try {
            $this->processCallback($request);
        } catch (\Throwable $exception) {
        }
        return redirect()->route('member.profile');
    }

    /**
     * @throws IdentityProviderException
     */
    function processCallback(Request $request)
    {
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->input('code'),
        ]);
        $resourceOwner = json_decode(json_encode($this->provider->getResourceOwner($accessToken)->toArray()));
        dd($resourceOwner);
    }

}
