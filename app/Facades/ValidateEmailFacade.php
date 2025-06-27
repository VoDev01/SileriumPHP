<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ValidateEmailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EmailValidationService';
    }
}