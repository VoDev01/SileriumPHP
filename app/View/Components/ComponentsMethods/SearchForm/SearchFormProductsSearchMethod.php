<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchFormProductsSearchMethod implements SearchFormInterface
{
    public static function search(Request $request, array $validated)
    {
        $seller = null;

        if (isset($request->sellerId))
            $seller =  Seller::where('id', $request->sellerId)->get()->first();

        $response = Cache::remember("search_products_{$validated['productName']}", env('CACHE_TTL'), function () use ($validated, $seller)
        {
            $response = Http::withoutVerifying()->withHeaders([
                'Accept' => 'application/json',
                'API-Key' => $request->api_key ?? null,
                'API-Secret' => $request->api_secret ?? null
            ])
                ->post(env('APP_URL') . '/api/v1/products/by_name_seller', [
                    'sellerName' => $validated['sellerName'],
                    'productName' => $validated['productName'],
                    'loadWith' => $validated['loadWith'],
                    'sellerId' => $seller->id ?? null
                ]);
            if ($response->ok())
                return $response->json('products');
            else
            {
                return ['message' => $response->body()];
            }
        });

        try
        {
            if (array_key_exists('message', $response))
            {
                Cache::delete("search_products_{$validated['productName']}");
                throw new NotFoundHttpException($response['message']);
            }
            else
            {
                if (key_exists('redirect', $validated))
                {
                    if ($request->session()->get('products') !== null)
                        $request->session()->forget('products');

                    $request->session()->put('products', $response->json('products'));
                    $request->session()->put('searchName', $validated['productName']);

                    return redirect()->route($validated['redirect']);
                }
                else
                    return response()->json($response->json('products'));
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
