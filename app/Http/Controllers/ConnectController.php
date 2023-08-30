<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Membership\MembershipLibrary;
use App\Models\Membership\User\User;
use App\Models\Membership\User\UserVatgerDetail;
use App\Providers\VATSIM\ConnectProvider;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use UnexpectedValueException;

class ConnectController extends Controller
{
    /**
     * The VATSIM Authentication Provider Instance
     */
    protected ConnectProvider $_provider;

    /**
     * Initialize the Controller with a new ConnectProvider instance
     */
    function __construct()
    {
        parent::__construct();
        $this->_provider = new ConnectProvider();
    }

    public function login(Request $request): RedirectResponse
    {
        $authenticationUrl = $this->_provider->getAuthorizationUrl();
        $request->session()->put('vatsim.authentication.connect.state', $this->_provider->getState());
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
            $accessToken = $this->_provider->getAccessToken('authorization_code', [
                'code' => $request->input('code'),
            ]);
        } catch (UnexpectedValueException $e) {
            Log::error('[ConnectController]::_verifyLogin::AccessToken::' . $e->getMessage());
            return redirect()->route('vatsim.authentication.connect.failed'); // Wrong format received from the Connect service
        } catch (IdentityProviderException $e) {
            Log::error('[ConnectController]::_verifyLogin::AccessToken::' . $e->getMessage());
            return redirect()->route('vatsim.authentication.connect.failed');
        }

        try {
            $resourceOwner = json_decode(json_encode($this->_provider->getResourceOwner($accessToken)->toArray()));
            // $resourceOwner = $this->_provider->getResourceOwner($accessToken);
        } catch (UnexpectedValueException $e) {
            Log::error('[ConnectController]::_verifyLogin::ResourceOwner::' . $e->getMessage());
            return redirect()->route('vatsim.authentication.connect.failed');
        }

        if (
            !isset($resourceOwner->data) ||
            !isset($resourceOwner->data->cid) ||
            !isset($resourceOwner->data->personal->name_first) ||
            !isset($resourceOwner->data->personal->name_last) ||
            !isset($resourceOwner->data->personal->email) ||
            $resourceOwner->data->oauth->token_valid !== 'true'
        ) {
            return redirect()->route('vatsim.authentication.connect.failed');
        }

        if (
            User::where('email', $resourceOwner->data->personal->email)
                ->where('id', '!=', $resourceOwner->data->cid)
                ->exists()
        ) {
            return redirect()->route('vatsim.authentication.connect.failed');
        }

        // All checks completed. Let's finally sign in the user
        $user = $this->_completeLogin($resourceOwner, $accessToken);

        Auth::login($user, true);

        if (!$user->settings->agreed) {
            return redirect()->route('check-terms');
        }

        return redirect()->intended('/');
    }

    /**
     * Complete the authentication process.
     */
    protected function _completeLogin(object $resourceOwner, object $accessToken): User
    {
        $user = User::updateOrCreate([
            'id' => $resourceOwner->data->cid,
            'firstname' => $resourceOwner->data->personal->name_first,
            'lastname' => $resourceOwner->data->personal->name_last,
            'email' => $resourceOwner->data->personal->email,
        ]);

        // If the user has given us permanent access to the data
        $user->passwords()->updateOrCreate([]);
        if ($resourceOwner->data->oauth->token_valid) {
            $user->passwords()->update([
                'oauth_access_token' => $accessToken->getToken(),
                'oauth_refresh_token' => $accessToken->getRefreshToken(),
                'oauth_token_expires' => $accessToken->getExpires(),
            ]);
        }

        $user->settings()->updateOrCreate([]);
        $user->settings()->update([
            'language' => Session::has('language') ? Session::get('language') : 'de',
        ]);
        
        $user->vatgerDetails()->updateOrCreate([]);

        $user->vatsimDetails()->updateOrCreate([]);
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

        $user->tokens()->delete();
        $user->createToken('api-token');

        return $user->fresh();
    }

    /**
     * Display a failed message and then return to the login
     */
    public function failed(Request $request): RedirectResponse
    {
        return redirect()
            ->route('landing')
            ->withErrors('Error logging in. Please try again.');
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
