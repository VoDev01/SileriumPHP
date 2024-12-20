<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidatePasswordHashService 
{
    public static function validate(Request $request, string $inputPassword, $user)
    {
        if (Hash::check($inputPassword, $user->password)) {
            $request->session()->regenerate();
            Auth::login($user, $request->remember_me);
            return ['success' => true];
        }
        else
            return ['success' => false, 'errors' => ['password' => ['Пароль не совпадает.']]];
    }
}