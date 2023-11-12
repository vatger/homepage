<?php

namespace App\Http;

use App\Http\Middleware\CookieConsentMiddleware;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\Membership\CheckHomepageBanned;
use App\Http\Middleware\Membership\CheckTermsMiddleware;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SysLogMiddleware;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use App\OpenApi\Middleware\Authenticate;
use App\OpenApi\Middleware\JsonResponse;
use App\OpenApi\Middleware\LogRequests;
use App\OpenApi\Middleware\OptionalAuthenticate;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            LocaleMiddleware::class,
            \Spatie\CookieConsent\CookieConsentMiddleware::class,
            PreventRequestsDuringMaintenance::class,
            SysLogMiddleware::class,
        ],

        'web_api' => [EnsureFrontendRequestsAreStateful::class, 'throttle:api', SubstituteBindings::class, PreventRequestsDuringMaintenance::class],

        'api' => [
            JsonResponse::class,
            SubstituteBindings::class,
            'throttle:api',
            PreventRequestsDuringMaintenance::class,
            OptionalAuthenticate::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => Middleware\Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'api_auth.optional' => OptionalAuthenticate::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        //'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'cookie.consent' => CookieConsentMiddleware::class,
        'banned' => CheckHomepageBanned::class,
        'check-terms' => CheckTermsMiddleware::class,
    ];
}
