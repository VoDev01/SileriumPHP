<?php

namespace App\Providers;

use App\Services\EmailValidationService;
use Illuminate\Support\ServiceProvider;

class EmailValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        return $this->app->bind('EmailValidationService', function(){
            return new EmailValidationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
