<?php

namespace App\Providers;

use App\Services\ReviewService;
use Illuminate\Support\ServiceProvider;

class ReviewProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ReviewService', function(){
            return new ReviewService();
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
