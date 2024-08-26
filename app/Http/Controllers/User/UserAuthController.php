<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\MakeUserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\UserRegisterRequest;
use App\Services\ValidatePasswordHashService;
use App\Facades\ValidateEmailFacade as ValidateEmail;
use App\Facades\ValidatePhoneFacade as ValidatePhone;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password as FacadesPassword;

class UserAuthController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }
    public function postLogin(UserLoginRequest $request)
    {
        if (Auth::viaRemember()) {
            $request->session()->regenerate();
            return redirect()->intended('/user/profile');
        } 
        else 
        {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();
            if(ValidatePasswordHashService::validate($request, $validated['password'], $user))
            {
                if(Gate::allows('access-admin-panel', $user))
                {
                    return redirect()->route('admin_index');
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
    public function postRegister(UserRegisterRequest $request)
    {
        $validated = $request->validate();
        $apiValidationMessage = "";
        if (!ValidateEmail::validate($validated['email'], $apiValidationMessage)) {
            return back()->withErrors(['email' => $apiValidationMessage]);
        } 
        if (!ValidatePhone::validate($validated['phone'], $apiValidationMessage)) {
            return back()->withErrors(['phone' => $apiValidationMessage]);
        }
        if ($request->pfp != null) {
            $pfpPath = Storage::putFile('pfp', $validated['pfp']);
        } else {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = MakeUserService::make($validated, $pfpPath);
        $user->save();
        return redirect()->route('login');
    }
}
