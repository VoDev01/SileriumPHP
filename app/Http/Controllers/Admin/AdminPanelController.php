<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminPanelController extends Controller
{
    public function home()
    {
        return view("admin.home");
    }
    public function profile()
    {
        $user = User::find(Auth::id());
        return view("admin.profile", ['user' => $user]);
    }
}
