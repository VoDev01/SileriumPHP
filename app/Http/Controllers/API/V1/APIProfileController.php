<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class APIProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        if($user->tokens !== null)
        {
            $token = $user->tokens->first()->id;
            session()->flash('accessToken', $token);
        }
        return view('api.profile', ['user' => $user]);
    }

    public function generateToken()
    {
        $user = Auth::user();
        if($user->tokens === null)
        {
            $user->createToken($user->email . ' token');
            $token = $user->tokens->first()->id;
        }
        else
            $token = $user->tokens->first()->id;
        return redirect()->back()->with('accessToken', $token);
    }

    public function refreshToken(Request $request)
    {
        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'scope' => '',
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Refreshed token.',
            'data' => $response->json(),
        ], 200);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
