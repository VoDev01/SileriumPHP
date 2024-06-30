<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersAdminPanelController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }
}
