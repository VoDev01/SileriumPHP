<?php

namespace App\Http;

use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

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
        //\App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            TrustProxies::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Auth\CheckBannedUser::class,
            'auth.refresh.token'
            //\App\Http\Middleware\TimezoneBasedOnIP::class
        ],

        'api' => [
            //\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            TrustProxies::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\Auth\AuthorizeApi::class,
            \App\Http\Middleware\Auth\CheckBannedUser::class,
            //\App\Http\Middleware\TimezoneBasedOnIP::class
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
        'authorize.api' => \App\Http\Middleware\Auth\AuthorizeApi::class,
        'authorize.admin' => \App\Http\Middleware\Auth\AuthorizeAdminPanel::class,
        'authorize.seller' => \App\Http\Middleware\Auth\AuthorizeSeller::class,
        'authorize.seller.admin' => \App\Http\Middleware\Auth\AuthorizeSellerAdmin::class,
        'authorize.user.routes' => \App\Http\Middleware\Auth\AuthorizeUserRoute::class,
        'banned' => \App\Http\Middleware\Auth\CheckBannedUser::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.refresh.token' => \App\Http\Middleware\Auth\RefreshUserToken::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'cache.media.headers' => \App\Http\Middleware\Utility\SetMediaCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \App\Http\Middleware\Utility\ThrottleTestRequests::class, #\Illuminate\Http\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
