<?php

namespace App\Providers;

use App\Services\ConvertCurrencyService;
use Illuminate\Support\ServiceProvider;

class ConvertCurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('ConvertCurrencyService', function(){
            return new ConvertCurrencyService();
        });
    }
}
