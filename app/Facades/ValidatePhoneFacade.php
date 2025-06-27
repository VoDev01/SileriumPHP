<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ValidatePhoneFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PhoneValidationService';
    }
}