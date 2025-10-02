<?php

namespace App\Http\Controllers\User;

use App\Actions\EncodeImageBinaryToBase64Action;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $converted = EncodeImageBinaryToBase64Action::encode($user->profilePicture);
        $user->profilePicture = $converted['base64'];
        return view('user.profile', ['user' => $user, 'ext' => $converted['ext']]);
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
