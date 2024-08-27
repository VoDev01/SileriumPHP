<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProductServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductService';
    }
}