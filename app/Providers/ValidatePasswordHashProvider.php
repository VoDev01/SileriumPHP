<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ValidatePasswordHashService;

class ValidatePasswordHashProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ValidatePasswordHashService', function(){
            return new ValidatePasswordHashService();
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
