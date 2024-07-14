<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class UsersAdminPanelController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }
    public function roles() 
    {
        $users = User::with('roles:role')->paginate(5);
        $roles = Role::all();
        return view('admin.users.roles', ['users' => $users, 'roles' => $roles]);
    }
    public function orders()
    {
        if(!session('user'))
            return view('admin.users.orders', ['userInfoReceived' => false]);
        else
            return view('admin.users.orders', ['userInfoReceived' => true, 'user' => session('user')]);
    }
    public function reviews()
    {
        if(!session('user'))
            return view('admin.users.reviews', ['userInfoReceived' => false]);
        else
            return view('admin.users.reviews', ['userInfoReceived' => true, 'user' => session('user')]);
    }
    public function findUsers(Request $request)
    {
        $params = $request->load_with . "/" . $request->email;
        if($request->name != null)
            $params = $params . "/" . $request->name;
        if($request->surname != null)
            $params = $params . "/" . $request->surname;
        if($request->id != null)
            $params = $params . "/" . $request->id;
        if($request->phone != null)
            $params = $params . "/" . $request->phone;
        $response = Http::get('https://silerium.com/api/v1/users/find/' .  $params)['users'];
        if($request->ajax())
        {
            return response()->json(['users' => $response]);
        }
        else
            return redirect()->route($request->redirect)->with('user', $response[0]);
    }
    public function postUserSearch(Request $request)
    {
        return redirect()->action([UsersAdminPanelController::class, 'findUsers'], 
        [
            'load_with' => $request->load_with, 
            'email' => $request->email, 
            'name' => $request->name,
            'surname' => $request->surname,
            'id' => $request->id,
            'phone' => $request->phone,
            'redirect' => $request->redirect
        ]);
    }
}
