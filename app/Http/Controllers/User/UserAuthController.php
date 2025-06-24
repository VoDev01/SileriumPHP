<?php

namespace App\Http\Controllers\User;

use App\Actions\HandleUserLoginAction;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\UserAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserLoginRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\User\UserRegisterRequest;
use App\Services\VerifyPhoneService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password as PasswordUtil;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        return view('user.auth.login', ['api' => $request->api]);
    }
    public function postLogin(UserLoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->get()->first();
        return HandleUserLoginAction::handle($user, $request, $validated);
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
        return redirect()->route('profile');
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
            return redirect()->route('profile');
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
    public function postRegister(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if ($request->pfp != null)
        {
            $pfpPath = Storage::putFile('pfp', $validated['pfp']);
        }
        else
        {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = UserAction::make($validated, $pfpPath);
        event(new Registered($user));
        return redirect()->route('login');
    }
}
