<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function profile()
    {
        $user = User::find(Auth::id());
        return view('user.profile', ['user' => $user]);
    }
    public function editProfile()
    {
        $user = User::find(Auth::id());
        return view('user.editprofile', ['user' => $user]);
    }
    public function postEditProfile(Request $request)
    {
        $user_val = $request->validate([
            'name' => ['min:5', 'max:30'],
            'surname' => ['min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users'],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'nullable'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        User::find(Auth::id())->update($user_val);
        return redirect()->route('profile');
    }
}
