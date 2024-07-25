<?php

namespace App\Providers;

use App\Services\CheckUserRoleService;
use Illuminate\Support\ServiceProvider;

class CheckUserRoleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CheckUserRoleService', function(){
            return new CheckUserRoleService();
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
