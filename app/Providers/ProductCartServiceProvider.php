<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Products\ProductCartService;

class ProductCartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ProductCartService', function(){
            return new ProductCartService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
