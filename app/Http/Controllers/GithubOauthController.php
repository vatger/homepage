<?php

namespace App\Http\Controllers;

use App\Providers\GithubOauthProvider;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GithubOauthController extends Controller
{
    protected GithubOauthProvider $provider;

    private string $state_session_key = 'github.oauth.state';

    public function __construct()
    {
        parent::__construct();
        $this->provider = new GithubOauthProvider;
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
            $name = $this->processCallback($request);
        } catch (\Throwable $exception) {
            return redirect()->route('member.profile')->withErrors('Github profile could not be linked');
        }

        return redirect()->route('member.profile')->with('success', 'Github profile '.$name.' linked');
    }

    /**
     * @throws IdentityProviderException
     */
    public function processCallback(Request $request)
    {
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->input('code'),
        ]);
        $resourceOwner = json_decode(json_encode($this->provider->getResourceOwner($accessToken)->toArray()));
        $this->current_user->settings->github_username = $resourceOwner->login;
        $this->current_user->settings->github_userid = $resourceOwner->id;
        $this->current_user->settings->save();
    }

    /*
        {
  +"login": "paulhollmann"
  +"id": 31701319
  +"node_id": "MDQ6VXNlcjMxNzAxMzE5"
  +"avatar_url": "https://avatars.githubusercontent.com/u/31701319?v=4"
  +"gravatar_id": ""
  +"url": "https://api.github.com/users/paulhollmann"
  +"html_url": "https://github.com/paulhollmann"
  +"followers_url": "https://api.github.com/users/paulhollmann/followers"
  +"following_url": "https://api.github.com/users/paulhollmann/following{/other_user}"
  +"gists_url": "https://api.github.com/users/paulhollmann/gists{/gist_id}"
  +"starred_url": "https://api.github.com/users/paulhollmann/starred{/owner}{/repo}"
  +"subscriptions_url": "https://api.github.com/users/paulhollmann/subscriptions"
  +"organizations_url": "https://api.github.com/users/paulhollmann/orgs"
  +"repos_url": "https://api.github.com/users/paulhollmann/repos"
  +"events_url": "https://api.github.com/users/paulhollmann/events{/privacy}"
  +"received_events_url": "https://api.github.com/users/paulhollmann/received_events"
  +"type": "User"
  +"site_admin": false
  +"name": "paulhol"
  +"company": "Technical University of Darmstadt"
  +"blog": ""
  +"location": "Darmstadt, Germany"
  +"email": null
  +"hireable": null
  +"bio": null
  +"twitter_username": null
  +"notification_email": null
  +"public_repos": 16
  +"public_gists": 1
  +"followers": 11
  +"following": 7
  +"created_at": "2017-09-06T15:20:21Z"
  +"updated_at": "2024-08-25T11:36:41Z"
  +"private_gists": 0
  +"total_private_repos": 29
  +"owned_private_repos": 29
  +"disk_usage": 1042108
  +"collaborators": 8
  +"two_factor_authentication": true
  +"plan": {#970 ▶}
}
    */

}
