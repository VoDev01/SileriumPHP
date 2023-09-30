<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ValidatePhone extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PhoneValidationService';
    }
}