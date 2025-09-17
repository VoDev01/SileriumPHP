<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormInterface;

class SearchFormProductsSearchMethod implements SearchFormInterface
{
    public static function search(Request $request, array $validated)
    {
        $seller = null;
        if(isset($request->sellerId))
            $seller =  Seller::where('id', $request->sellerId)->get()->first();
        $response = Http::withoutVerifying()->withHeaders(['Accept' => 'application/json', 
        'API-Key' => $request->api_key ?? null, 
        'API-Secret' => $request->api_secret ?? null])
        ->post(env('APP_URL') . '/api/v1/products/by_name_seller', [
            'sellerName' => $validated['sellerName'],
            'productName' => $validated['productName'],
            'loadWith' => $validated['loadWith'],
            'sellerId' => $seller->id ?? null
        ]);
        if (!$response->ok())
        {
            if (key_exists('redirect', $validated))
            {
                return redirect()->route($validated['redirect'])->with(['message' => $response->body()]);
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
                if($request->session()->get('products') !== null)
                    $request->session()->forget('products');
                $request->session()->put('products', $response->json('products'));
                $request->session()->put('searchName', $validated['productName']);
                return redirect()->route($validated['redirect']);
            }
            else
                return response()->json($response->json('products'));
        }
    }
}
