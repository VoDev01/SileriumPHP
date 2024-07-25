<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ConvertCurrencyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ConvertCurrencyService';
    }
}