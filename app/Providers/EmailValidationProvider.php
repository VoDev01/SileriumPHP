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
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        return $this->app->bind('EmailValidationService', function(){
            return new EmailValidationService();
        });
    }
}
