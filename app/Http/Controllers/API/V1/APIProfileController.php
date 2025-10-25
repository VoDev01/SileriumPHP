<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class APIProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user('api');
        return view('api.profile', ['user' => $user]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
