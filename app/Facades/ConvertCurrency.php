<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ConvertCurrency extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ConvertCurrencyService';
    }
}