<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Facades\ValidateEmail;
use App\Facades\ValidatePhone;
use App\Services\MakeUserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Services\ValidatePasswordHashService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UserAuthController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }
    public function postLogin(Request $request)
    {
        if (Auth::viaRemember()) {
            $request->session()->regenerate();
            return redirect()->intended('/user/profile');
        } 
        else 
        {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users', 'min: 5', 'max: 100'],
                'password' => ['required', 'min: 10']
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            $validated = $validator->validated();
            $user = User::where('email', $validated['email'])->first();
            if(ValidatePasswordHashService::validate($request, $validated['password'], $user))
            {
                if(Gate::allows('access-admin-panel', $user))
                {
                    return redirect()->route('admin_home');
                }
                else
                {
                    return redirect()->route('profile');
                }
            }
            else
            {
                return redirect()->back()->withErrors(['password' => 'Неверный пароль']);
            }
            /*
            /*$response = ValidatePasswordHashService::validate($request, $validated['password'], $user);
            if(!$response['success'])
                return response()->json($response);
            else
                return redirect()->route('profile');*/
        }
    }
    public function forgotPassword()
    {
        return view('user.auth.forgotpassword');
    }
    public function postForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = FacadesPassword::sendResetLink(
            $request->only('email')
        );

        return $status === FacadesPassword::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    public function resetPassword(string $token)
    {
        return view('user.auth.resetpassword', ['token' => $token]);
    }
    public function postResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()]
        ]);

        $status = FacadesPassword::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === FacadesPassword::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function verifyEmail()
    {
        return view('user.auth.verifyemail');
    }
    public function emailVerificationHandler(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('profile');
    }
    public function resendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back();
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
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        $apiValidationMessage = "";
        if (!ValidateEmail::validate($user_val['email'], $apiValidationMessage)) {
            return back()->withErrors(['email' => $apiValidationMessage]);
        } 
        if($user_val['phone'] != null)
        {
            if (!ValidatePhone::validate($user_val['phone'], $apiValidationMessage)) {
                return back()->withErrors(['phone' => $apiValidationMessage]);
            }
        }
        if ($request->pfp != null) {
            $pfpPath = Storage::putFile('pfp', $user_val['pfp']);
        } else {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = MakeUserService::make($user_val, $pfpPath);
        $user->save();
        return redirect()->route('login');
    }
}
