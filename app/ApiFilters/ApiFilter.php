<?php 
namespace App\ApiFilters;

use Illuminate\Http\Request;

abstract class ApiFilter
{
    protected $safeParams = [];

    protected $operatorMap = [];

    abstract public function transform(Request $request);
}