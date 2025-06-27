<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
    }

    public function map()
    {
        $this->mapAPIRoutes();
        $this->mapWebRoutes();
        $this->mapPaymentRoutes();
        $this->mapProductsRoutes();
        $this->mapAdminRoutes();
        $this->mapUserRoutes();
        $this->mapSellerRoutes();
    }
    public function mapAPIRoutes()
    {
        Route::middleware('api')
            ->prefix('/api/v1')
            ->group(base_path('routes/api.php'), ['guard' => 'api']);
    }
    public function mapPaymentRoutes()
    {
        Route::middleware(['web', 'auth', 'auth:web'])
            ->prefix('payment')
            ->group(base_path('routes/web/payment.php'));
    }
    public function mapProductsRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web/products.php'));
    }
    public function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web/web.php'));
    }
    public function mapAdminRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->prefix('admin')
            ->group(base_path('routes/web/admin.php'));
    }
    public function mapUserRoutes()
    {
        Route::middleware('web')
            ->prefix('user')
            ->group(base_path('routes/web/user.php'));
    }
    public function mapSellerRoutes()
    {
        Route::middleware('web')
            ->prefix('seller')
            ->group(base_path('routes/web/seller.php'));
    }
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
