<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use Illuminate\Support\Facades\Http;

class SearchFormProductsSearchMethod
{
    public static function searchProducts(array $validated)
    {
        $response = Http::post('silerium.com/api/v1/products/by_name_seller', [
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
            {
                if (key_exists('searchKey', $validated))
                {
                    session(['products' => $response->json('products'), 'searchKey' => $validated['searchKey']]);
                    return redirect()->route($validated['redirect'], ['searchKey' => $validated['searchKey']]);
                }
                else
                {
                    session(['products' => $response->json('products')]);
                    return redirect()->route($validated['redirect']);
                }
            }
            else
            {
                return response()->json($response->json('products'));
            }
        }
    }
}
