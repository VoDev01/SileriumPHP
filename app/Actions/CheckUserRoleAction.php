<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckUserRoleAction
{
    public function check(string $role)
    {
        if(Auth::user())
        {
            $user = User::with('roles')->where('id', Auth::id())->first();
            foreach($user->roles as $userRole)
            {
                if($userRole->role == $role)
                    return true;
            }
        }
        return false;
    }
}