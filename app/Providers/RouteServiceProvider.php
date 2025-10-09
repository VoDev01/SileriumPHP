<?php

namespace App\Providers;

use App\Models\Product;
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

        Route::bind('product', function ($value)
        {
            return Product::where('id', $value)->orWhere('ulid', $value)->firstOrFail();
        });
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
        Route::middleware(['web', 'auth', 'auth:web', 'throttle:global'])
            ->prefix('payment')
            ->group(base_path('routes/web/payment.php'));
    }
    public function mapProductsRoutes()
    {
        Route::middleware(['web', 'throttle:global'])
            ->group(base_path('routes/web/products.php'));
    }
    public function mapWebRoutes()
    {
        Route::middleware(['web', 'throttle:global'])
            ->group(base_path('routes/web/web.php'));
    }
    public function mapAdminRoutes()
    {
        Route::middleware(['web', 'auth', 'throttle:global'])
            ->prefix('admin')
            ->group(base_path('routes/web/admin.php'));
    }
    public function mapUserRoutes()
    {
        Route::middleware(['web', 'throttle:global'])
            ->prefix('user')
            ->group(base_path('routes/web/user.php'));
    }
    public function mapSellerRoutes()
    {
        Route::middleware(['web', 'throttle:global'])
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
        RateLimiter::for('global', function (Request $request)
        {
            return Limit::perMinute(500);
        });

        RateLimiter::for('api', function (Request $request)
        {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request)
        {
            return Limit::perMinute(5)->by($request->ip())->response(function () use ($request)
            {
                return response()->json(['errors' => ['attempts_available_in' => 'Превышено количество попыток. Повторить можно будет через 60 сек']], 429);
            });
        });
    }
}
