<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FallbackController extends Controller
{
    public function __invoke()
    {
        return view('errors.general', ['message' => 'Страница не найдена', 'status' => 404]);
    }
}
