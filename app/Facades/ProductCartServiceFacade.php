<?php 
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProductCartServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductCartService';
    }
}