<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ValidatePasswordHashFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ValidatePasswordHashService';
    }
}