<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Validates input password with user password hash in database
 */
class ValidatePasswordHashAction
{
    public static function validate(string $inputPassword, User $user)
    {
        if (Hash::check($inputPassword, $user->password))
        {
            return ['success' => true];
        }
        else
            return ['success' => false, 'errors' => ['password' => 'Пароль не совпадает.']];
    }
}
