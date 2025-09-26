<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormInterface;

class SearchFormUsersSearchMethod implements SearchFormInterface
{
    public static function search(Request $request, array $validated)
    {
        $response = Http::asJson()->withHeaders([
            'API-Key' => $request->api_key,
            'API-Secret' => $request->api_secret
            ])
            ->post(env('APP_URL') . '/api/v1/user/search', [
            'email' => $validated["email"],
            'loadWith' => $validated["loadWith"],
            'name' => $validated["name"],
            'surname' => $validated["surname"],
            'id' => $validated["id"],
            'phone' => $validated["phone"]
        ]);
        if ($request->ajax() || App::environment('testing'))
        {
            if ($response->ok())
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

                    if ($request->session()->get('users') !== null)
                        $request->session()->forget('users');
                    $request->session()->put('users', $response->json('users'));
                    return redirect()->route($validated['redirect']);
                }
                else
                    return response()->json($response->json('users'));
            }
        }
    }
}
