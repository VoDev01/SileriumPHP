<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\API\Profile\APIRefreshTokenRequest;

class APIProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        if ($user->tokens !== null)
        {
            $token = $user->tokens->first()->id;
            session()->flash('accessToken', $token);
        }
        return view('api.profile', ['user' => $user]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
