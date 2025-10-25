<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\BannedUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\VerifyPhoneService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\User\UserLoginRequest;
use App\Services\Auth\HandleUserLoginService;
use App\Http\Requests\User\UserRegisterRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password as PasswordUtil;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Auth::user() !== null ? Auth::user()->ulid : null; 
        if(BannedUser::where('user_id', $user)->get()->first())
            return view('user.auth.loginbanned', ['api' => $request->api]);

        return view('user.auth.login', ['api' => $request->api]);
    }

    public function signInWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function signInWithYandex(Request $request)
    {
        return HandleUserLoginService::loginOauth($request);
    }

    public function postLogin(UserLoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->get()->first();
        return HandleUserLoginService::login($user, $request, $validated);
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
        $user = Auth::user() !== null ? Auth::user()->ulid : null; 
        if(BannedUser::where('user_id', $user)->get()->first())
            return view('user.auth.registerbanned');

        return view('user.auth.register');
    }
    public function postRegister(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if ($request->pfp != null)
        {
            $userNum = User::all()->count() + 1;
            $pfpPath = Storage::putFile('/images/pfp/pfp_' . substr_replace('0000000000', $userNum, strlen((string)abs($userNum))), $validated['pfp']);
        }
        else
        {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = (new UserRepository)->create($validated, $pfpPath);
        event(new Registered($user));
        $request->session()->regenerate();
        return redirect()->route('login');
    }
}
