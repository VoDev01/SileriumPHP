<?php

namespace App\Providers;

use App\Services\PhoneValidationService;
use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;

class PhoneValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PhoneValidationService::class, function(){
            return new PhoneValidationService();
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
