<?php

namespace App\Http\Controllers;

use App\Libraries\MembershipLibrary;
use App\Models\Membership\User\User;
use App\Providers\ConnectProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use UnexpectedValueException;

class ConnectController extends Controller
{
    /**
     * The VATSIM Authentication Provider Instance
     */
    protected ConnectProvider $provider;

    /**
     * Initialize the Controller with a new ConnectProvider instance
     */
    function __construct()
    {
        parent::__construct();
        $this->provider = new ConnectProvider();
    }

    public function login(Request $request): RedirectResponse
    {
        $authenticationUrl = $this->provider->getAuthorizationUrl();
        $request->session()->put('vatsim.authentication.connect.state', $this->provider->getState());
        return redirect()->away($authenticationUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->input('state') !== session()->pull('vatsim.authentication.connect.state')) {
            // Within this state there is no state. The only option here is to start again.
            $request->session()->invalidate(); // Invalidate and regenerate the session
            return redirect()->route('vatsim.authentication.connect.login');
        }
        return $this->_verifyLogin($request);
    }

    /**
     * Check that all required data is received from the VATSIM Connect authentication system
     */
    private function _verifyLogin(Request $request): RedirectResponse
    {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code'),
            ]);
        } catch (UnexpectedValueException $e) {
            Log::error('[ConnectController]::_verifyLogin::AccessToken::' . $e->getMessage());
            return redirect()
                ->route('landing')
                ->withErrors('Error logging in (UnexpectedValueException). Please try again.');
            // Wrong format received from the Connect service
        } catch (IdentityProviderException $e) {
            Log::error('[ConnectController]::_verifyLogin::AccessToken::' . $e->getMessage());
            return redirect()
                ->route('landing')
                ->withErrors('Error logging in (IdentityProviderException). Please try again.');
        }

        try {
            $resourceOwner = json_decode(json_encode($this->provider->getResourceOwner($accessToken)->toArray()));
        } catch (UnexpectedValueException $e) {
            Log::error('[ConnectController]::_verifyLogin::ResourceOwner::' . $e->getMessage());
            return redirect()
                ->route('landing')
                ->withErrors('Error logging in (ResourceOwnerUnexpectedValueException). Please try again.');
        }

        if (
            !isset($resourceOwner->data) ||
            !isset($resourceOwner->data->cid) ||
            !isset($resourceOwner->data->personal->name_first) ||
            !isset($resourceOwner->data->personal->name_last) ||
            !isset($resourceOwner->data->personal->email) ||
            $resourceOwner->data->oauth->token_valid !== 'true'
        ) {
            return redirect()
                ->route('landing')
                ->withErrors('Error logging in (ResourceOwnerEmpty). Please try again.');
        }

        if (
            User::where('email', $resourceOwner->data->personal->email)
                ->where('id', '!=', $resourceOwner->data->cid)
                ->exists()
        ) {
            return redirect()
                ->route('landing')
                ->withErrors('Login Error: Wende dich an den VATSIM Germany Support.');
        }

        // All checks completed. Let's finally sign in the user
        $user = $this->_completeLogin($resourceOwner, $accessToken);

        Auth::login($user, true);

        if (!$user->settings->agreed) {
            return redirect()->route('check-terms');
        }

        try {
            MembershipLibrary::update($user, cache: false, api_refresh: true);
        } catch (Exception $e) {
        }

        return redirect()->intended(route('member.profile'));
    }

    /**
     * Complete the authentication process.
     */
    protected function _completeLogin(object $resourceOwner, object $accessToken): User
    {
        $user = User::where('id', $resourceOwner->data->cid)->firstOrNew();
        $user->id = $resourceOwner->data->cid;
        $user->firstname = $resourceOwner->data->personal->name_first;
        $user->lastname = $resourceOwner->data->personal->name_last;
        $user->email = $resourceOwner->data->personal->email;
        $user->save();

        // If the user has given us permanent access to the data
        if ($resourceOwner->data->oauth->token_valid) {
            $user->passwords()->update([
                'oauth_access_token' => $accessToken->getToken(),
                'oauth_refresh_token' => $accessToken->getRefreshToken(),
                'oauth_token_expires' => $accessToken->getExpires(),
            ]);
        }

        $user->settings()->update([
            'language' => Session::has('language') ? Session::get('language') : 'de',
        ]);

        $user->vatsimDetails()->update([
            'rating_atc' => $resourceOwner->data->vatsim->rating->id,
            'rating_pilot' => $resourceOwner->data->vatsim->pilotrating->id,
            'country_code' => $resourceOwner->data->personal->country->id,
            'country_name' => $resourceOwner->data->personal->country->name,
            'region_code' => $resourceOwner->data->vatsim->region->id,
            'region_name' => $resourceOwner->data->vatsim->region->name,
            'division_code' => $resourceOwner->data->vatsim->division->id,
            'division_name' => $resourceOwner->data->vatsim->division->name,
            'subdivision_code' => config('app.env') !== 'production' ? 'GER' : $resourceOwner->data->vatsim->subdivision->id,
            'subdivision_name' => config('app.env') !== 'production' ? 'Germany' : $resourceOwner->data->vatsim->subdivision->name,
        ]);

        MembershipLibrary::seen($user);

        //$user->tokens()->delete();
        //$user->createToken('api-token');

        return $user->fresh();
    }

    /**
     * End an authenticated session
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()
            ->route('landing')
            ->with('success', 'Logged out successfully.');
    }
}
