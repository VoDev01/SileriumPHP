<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
    public function profile()
    {
        $user = null;
        if(Auth::user() !== null)
        {
            $user = User::find(Auth::id());
        }
        else
        {
            $googleUser = Socialite::driver('google')->user();
            if (User::whereEmail($googleUser->getEmail())->get()->first !== null)
            {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail()
                ]);
            }
            else
                $user = $googleUser;
        }
        return view('user.profile', ['user' => $user]);
    }
    public function editProfile()
    {
        $user = User::find(Auth::id());
        return view('user.editprofile', ['user' => $user]);
    }
    public function postEditProfile(Request $request)
    {
        $validated = $request->validated();
        User::find(Auth::id())->update($validated);
        return redirect()->route('profile');
    }
}
