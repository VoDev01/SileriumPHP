<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ManualPaginatorService;

class ManualPaginatorProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ManualPaginatorService', function(){
            return new ManualPaginatorService();
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
