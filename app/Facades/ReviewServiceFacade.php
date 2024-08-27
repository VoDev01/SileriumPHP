<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReviewServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ReviewService';
    }
}