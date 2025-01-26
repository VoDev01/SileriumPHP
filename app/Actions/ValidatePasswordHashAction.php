<?php

namespace App\Actions;

use App\Models\ApiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidatePasswordHashAction
{
    public static function validate(string $inputPassword, User|ApiUser $user, Request $request = null)
    {
        if (Hash::check($inputPassword, $user->password))
        {
            if (isset($request))
            {
                $request->session()->regenerate();
                Auth::login($user, $request->remember_me);
            }
            return ['success' => true];
        }
        else
            return ['success' => false, 'errors' => ['password' => 'Пароль не совпадает.']];
    }
}
