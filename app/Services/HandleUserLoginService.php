<?php

namespace App\Services;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Actions\ValidatePasswordHashAction;
use Illuminate\Support\Facades\Cache;

class HandleUserLoginService
{
    /**
     * Redicter user to his profile page based on his role
     *
     * @param Request $request
     * @param array $validated If input has api redirect to api profile
     * @param User $user
     * @return void
     */
    private static function redirectToRoleProfile(Request $request, array $validated, User $user)
    {
        if (Gate::allows('accessAdminModerator', $user))
        {
            return response()->json(['redirect' => '/admin/index']);
        }
        else if (Gate::allows('accessSeller', $user))
        {
            $request->session()->put('seller_id', Seller::where('user_id', Auth::id())->get()->first()->id);
            return response()->json(['redirect' => '/seller/account']);
        }
        else if (isset($validated['api']))
        {
            return response()->json(['redirect' => '/api/v1/profile']);
        }
        else
        {
            return response()->json(['redirect' => '/user/profile']);
        }
    }
    public static function login(User $user, Request $request, array $validated)
    {
        if (key_exists('remember_me', $validated))
        {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $user->password], $validated['remember_me']))
            {
                $request->session()->regenerate();
                return self::redirectToRoleProfile($request, $validated, Auth::user());
            }
        }
        else
        {
            $response = ValidatePasswordHashAction::validate($validated['password'], $user, $request);
            if ($response['success'])
            {
                $request->session()->regenerate();
                Auth::login($user);
                return self::redirectToRoleProfile($request, $validated, $user);
            }
            else
                return response()->json($response, 403);
        }
    }

    /**
     * Login user using foreign oAuth api. Yandex is used right now
     *
     * @param Request $request
     * @return void
     */
    public static function loginOauth(Request $request)
    {
        if (!$request->hasAny(['code', 'state']))
        {
            $csrf_state = Str::random(rand(16, 128));
            Cache::add('csrf_state', $csrf_state);
            $redirect = "https://oauth.yandex.ru/authorize?" . http_build_query([
                "response_type" => "code",
                "client_id" => env('YANDEX_CLIENT_ID'),
                "redirect_uri" => "https://silerium.ru/user/sign_in_yandex",
                "scope" => [
                    "login:birthday",
                    "login:email",
                    "login:info"
                ],
                "optional_scopes" => [
                    "login:default_phone",
                    "login:avatar"
                ],
                "force_confirm" => true,
                "state" => $csrf_state,
            ]);
            header("Location: $redirect");
            exit;
        }
        else
        {
            if ($request->state === Cache::get('csrf_state'))
            {
                Cache::delete('csrf_state');
                $yandexToken = Http::acceptJson()->post("https://oauth.yandex.ru/", [
                    "client_id" => env('YANDEX_CLIENT_ID'),
                    "client_secret" => env('YANDEX_CLIENT_SECRET'),
                    "grant_type" => "authorization_code",
                    "code" => $request->code,
                ]);
                $yandexToken = $yandexToken->body();
                $yandexUser = Http::acceptJson()->withHeaders(['Authorization' => "OAuth {$yandexToken['access_token']}"])
                    ->get("https://login.yandex.ru/info", ['format' => 'json']);
                $yandexUser = $yandexUser->body();
            }
            else
                return abort(403);
        }
    }
}
