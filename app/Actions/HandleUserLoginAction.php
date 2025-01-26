<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HandleUserLoginAction
{
    public static function handle(User $user, Request $request, array $validated)
    {
        if (key_exists('remember_me', $validated))
        {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $user->password], $validated['remember_me']))
            {
                $request->session()->regenerate();
                if (Gate::allows('access-admin-moderator'))
                {
                    return redirect()->route('admin.index');
                }
                else if (Gate::allows('access-seller'))
                {
                    $request->session()->put('seller_id', Seller::where('user_id', Auth::id())->get()->first()->id);
                    return redirect()->route('seller.account');
                }
                else
                {
                    return redirect()->intended('/user/profile');
                }
            }
        }
        else
        {
            $response = ValidatePasswordHashAction::validate($validated['password'], $user, $request);
            if ($response['success'])
            {
                Auth::login($user);
                if (Gate::allows('access-admin-moderator', $user))
                {
                    return response()->json(['redirect' => '/admin/index']);
                }
                else if (Gate::allows('access-seller'))
                {
                    $request->session()->put('seller_id', Seller::where('user_id', Auth::id())->get()->first()->id);
                    return response()->json(['redirect' => '/seller/account']);
                }
                else
                {
                    return response()->json(['redirect' => '/user/profile']);
                }
            }
            else
                return response()->json($response, 422);
        }
    }
}
