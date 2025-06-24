<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SearchFormProductsSearchMethod
{
    public static function searchProducts(Request $request, array $validated)
    {
        $user = User::with('apiKey')->where('ulid', Auth::user()->ulid)->get()->first();
        $response = Http::withoutVerifying()->asJson()->withHeaders(['API-Secret' => $request->api_secret])->post(env('APP_URL') . '/api/v1/products/by_name_seller', [
            'sellerName' => $validated['sellerName'],
            'productName' => $validated['productName'],
            'loadWith' => $validated['loadWith']
        ]);
        if (!$response->ok())
        {
            if (key_exists('redirect', $validated))
            {
                return redirect()->route($validated['redirect'])->with(['message' => $response->json('message')]);
            }
            else
            {
                return response()->json(['message' => $response->json('message')]);
            }
        }
        else
        {
            if (key_exists('redirect', $validated))
                return redirect()->route($validated['redirect'])->with('products', $response->json('products'));
            else
                return response()->json($response->json('products'));
        }
    }
}
