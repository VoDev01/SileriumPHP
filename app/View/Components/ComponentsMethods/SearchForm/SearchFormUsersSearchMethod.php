<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SearchFormUsersSearchMethod
{
    public static function searchUsers(Request $request, array $validated)
    {
        $user = User::with('apiKey')->where('ulid', Auth::user()->ulid)->get()->first();
        $response = Http::asJson()->withBasicAuth($user->email, $user->password)->withHeaders(['API-Key' => $user->apiKey->api_key])->post('silerium.com/api/v1/user/search', [
            'email' => $validated["email"],
            'loadWith' => $validated["loadWith"],
            'name' => $validated["name"],
            'surname' => $validated["surname"],
            'id' => $validated["id"],
            'phone' => $validated["phone"]
        ]);
        if ($request->ajax() || App::environment('testing'))
        {
            if($response->ok())
                return ['users' => $response->json('users')];
            else
                return ['message' => $response->json('message') ?? 'Пользователи не были найдены.'];
        }
        else
        {
            if (!$response->ok())
            {
                if (key_exists('redirect', $validated))
                {
                    return redirect()->route($validated['redirect'])->with(['message' => $response->json('message') ?? 'Пользователи не были найдены.']);
                }
                else
                {
                    return response()->json(['message' => $response->json('message') ?? 'Пользователи не были найдены.']);
                }
            }
            else
            {
                if (key_exists('redirect', $validated))
                {
                    if (key_exists('searchKey', $validated))
                    {
                        session(['users' => $response->json('users'), 'searchKey' => $validated['searchKey']]);
                        return redirect()->route($validated['redirect'], ['searchKey' => $validated['searchKey']]);
                    }
                    else
                    {
                        session(['users' => $response->json('users')]);
                        return redirect()->route($validated['redirect']);
                    }
                }
                else
                {
                    return response()->json($response->json('users'));
                }
            }
        }
    }
}