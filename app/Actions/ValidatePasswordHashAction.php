<?php

namespace App\Actions;

use App\Models\ApiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidatePasswordHashAction
{
    public static function validate(string $inputPassword, User|ApiUser $user)
    {
        if (Hash::check($inputPassword, $user->password))
        {
            return ['success' => true];
        }
        else
            return ['success' => false, 'errors' => ['password' => 'Пароль не совпадает.']];
    }
}
