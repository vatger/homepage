<?php

namespace App\Http;

use App\Http\Middleware\ApiJsonResponse;
use App\Http\Middleware\ApiOptionalAuthenticate;
use App\Http\Middleware\CookieChecker;
use App\Http\Middleware\Cors;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\Membership\CheckGDPRMiddleware;
use App\Http\Middleware\Membership\CheckHomepageBanned;
use App\Http\Middleware\Membership\CheckSDPMiddleware;
use App\Http\Middleware\Membership\CheckTermsMiddleware;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SysLogMiddleware;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Statikbe\CookieConsent\CookieConsentMiddleware;

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
            StartSession::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            LocaleMiddleware::class,
            CookieConsentMiddleware::class,
            PreventRequestsDuringMaintenance::class,
            SysLogMiddleware::class,
            'check-terms',
        ],

        'web_api' => [
            'throttle:web_api',
            SubstituteBindings::class,
            PreventRequestsDuringMaintenance::class,
        ],

        'api' => [
            ApiJsonResponse::class,
            SubstituteBindings::class,
            'throttle:api',
            ApiOptionalAuthenticate::class,
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
        'api_auth.optional' => ApiOptionalAuthenticate::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        // 'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'csrf' => VerifyCsrfToken::class,
        'pending_removal' => CheckGDPRMiddleware::class,
        'banned' => CheckHomepageBanned::class,
        'check-terms' => CheckTermsMiddleware::class,
        'staff_data_protection' => CheckSDPMiddleware::class,
        'cookie.consent' => CookieConsentMiddleware::class,
        'cookie.redirect' => CookieChecker::class,
        'cors' => Cors::class,
    ];
}
