<?php

namespace App\Providers;

use App\Services\OrderItemsService;
use Illuminate\Support\ServiceProvider;

class OrderItemsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('OrderItemsService', function(){
            return new OrderItemsService();
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
