<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidatePasswordHashService 
{
    public static function validate($request, string $inputPassword, User $user)
    {
        if (Hash::check($inputPassword, $user->password)) {
            $request->session()->regenerate();
            Auth::login($user, $request->remember_me);
            return response()->json(['success' => true, 'redirect' => '/user/profile'], 200);
        } 
        else 
        {
            return response()->json(['success' => false, 'errors' => 'Пароль не совпадает'], 400);
        }
    }
}