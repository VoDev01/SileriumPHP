<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIHomeController extends Controller
{
    public function index()
    {
        return view('apihome');
    }
}
