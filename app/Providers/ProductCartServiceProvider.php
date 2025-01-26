<?php

namespace App\Providers;

use App\Services\ProductCartService;
use Illuminate\Support\ServiceProvider;

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
