<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckUserRoleService
{
    public function check(string $role)
    {
        if(Auth::user())
        {
            $user = User::with('roles')->where('id', Auth::id())->get();
            foreach($user->roles as $userRole)
            {
                if($userRole->role == $role)
                    return true;
            }
        }
        return false;
    }
}