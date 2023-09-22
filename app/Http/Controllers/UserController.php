<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }
    public function postLogin(Request $request)
    {
        if(Auth::viaRemember())
        {
            $request->session()->regenerate();
            return redirect()->intended('/user/profile');
        }
        else
        {
            $user_val = $request->validate([
                'email' => ['min: 5', 'max: 100', 'required', 'email'],
                'password' => ['required', 'min: 10']
            ]);
            $user = User::all()->where('email', $user_val['email'])->first();
            if($user == null)
                return back()->withErrors([
                    'email' => 'Неправильный email.'
                ]);
            if(Hash::check($user_val['password'], $user->password))
            {
                $request->session()->regenerate();
                Auth::login($user, $request->remember_me);
                return redirect()->intended('profile');
            }
            else
            {
                return back()->withErrors([
                    'password' => 'Неправильный пароль.'
                ]);
            }
        }
    }
    public function register()
    {
        return view('user.auth.register');
    }
    public function postRegister(Request $request)
    {
        $user_val = $request->validate([
            'name' => ['min:5', 'max:30', 'required'],
            'surname' => ['min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'profilePicture' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        if($request->pfp != null)
        {
           $pfpPath = Storage::putFile('pfp', $user_val['pfp']);
        }
        else
        {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = User::create([
            'name' => $user_val['name'],
            'surname' => $user_val['surname'],
            'email' => $user_val['email'],
            'password' => Hash::make($user_val['password']),
            'country' => $user_val['country'],
            'birthDate' => $user_val['birthDate'],
            'city' => $user_val['city'],
            'homeAdress' => $user_val['homeAdress'],
            'phone' => $user_val['phone'],
            'profilePicture' => $pfpPath,
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now()
        ]);
        $user->save();
        return redirect()->route('login');
    }
    public function profile()
    {
        $user = User::where('id', Auth::id())->first();
        return view('user.profile', $user);
    }
}
