<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CheckUserRoleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CheckUserRoleService';
    }
}