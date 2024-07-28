<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OrderServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OrderService';
    }
}