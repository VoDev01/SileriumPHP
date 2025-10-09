<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchFormUsersSearchMethod implements SearchFormInterface
{
    public static function search(Request $request, array $validated)
    {
        try
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
                    throw new NotFoundHttpException($response->json('message') ?? 'Пользователи не были найдены.');
            }
            else
            {
                if (!$response->ok())
                {
                    throw new NotFoundHttpException($response->json('message') ?? 'Пользователи не были найдены.');
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
        catch (NotFoundHttpException $e)
        {
            if (key_exists('redirect', $validated))
            {
                return redirect()->route($validated['redirect'])->with(['message' => $e->getMessage()]);
            }
            else
            {
                return response()->json(['message' => $e->getMessage()]);
            }
        }
    }
}
