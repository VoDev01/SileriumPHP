<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ManualPaginatorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ManualPaginatorService';
    }
}