<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\VerifyPhoneService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Actions\ValidatePasswordHashAction;
use App\Http\Requests\API\Profile\APILoginRequest;
use App\Http\Requests\API\Profile\APIRegisterRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password as PasswordUtil;

class APIAuthController extends Controller
{
    public function login()
    {
        return view('api.login');
    }
    public function postLogin(APILoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->get()->first();
        $response = ValidatePasswordHashAction::validate($validated['password'], $user);
        if ($response['success'])
        {
            Auth::guard('api')->login($user);
            //return response()->json(['redirect' => '/api/v1/profile']);
            return redirect()->route('api.profile');
        }
        else
        {
            return response()->json($response, 422);
        }
    }
    public function forgotPassword()
    {
        return view('user.auth.forgotpassword');
    }
    public function postForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = PasswordUtil::sendResetLink(
            $request->only('email')
        );

        return $status === PasswordUtil::RESET_LINK_SENT
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
            'password' => ['required', 'confirmed', Password::min(10)->numbers(), 'max: 50']
        ]);

        $status = PasswordUtil::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password)
            {
                $user->forceFill([
                    'password' => Hash::make($password, ['rounds' => 10])
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordUtil($user));
            }
        );

        return $status === PasswordUtil::PASSWORD_RESET
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
        return redirect()->route('api.profile');
    }
    public function resendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back();
    }
    public function verifyPhone()
    {
        VerifyPhoneService::send();
        session('link_sent', false);
        return view('user.auth.verifyphone');
    }
    public function resendPhoneVerification()
    {
        VerifyPhoneService::send();
        return back()->with('link_sent', true);
    }
    public function validatePhoneVerification(int $code)
    {
        if (VerifyPhoneService::$verification_code == $code)
        {
            Auth::user()->phoneVerified = true;
            return redirect()->route('api.profile');
        }
        else
        {
            return back()->withErrors("Код неверный. Повторите попытку.", 'verification_code');
        }
    }
    public function register()
    {
        return view('user.auth.register');
    }
    public function postRegister(APIRegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        event(new Registered($user));
        return redirect()->route('api.login');
    }
}
