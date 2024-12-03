<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsDeleteRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

class APIProductsController extends Controller
{
    public function index(int $itemsPerPage = 15)
    {
        $products = Product::orderBy('id')->paginate($itemsPerPage);
        return response()->json($products->toArray(), 200);
    }
    public function show(int $id)
    {
        $product = Product::where('id', $id)->orderBy('id')->get();
        if ($product != null)
            return response()->json(['product' => $product], 200);
        else
            return response()->json(['message' => 'No product was found with this id'], 400);
    }
    public function create(APIProductsCreateRequest $request)
    {
        $validated = $request->validated();
        $product = ProductService::make($validated);
        return response()->json(['Url' => 'https://silerium.com/catalog/product/' . $product->ulid], 200);
    }
    public function update(APIProductsUpdateRequest $request)
    {
        $validated = $request->validated();
        $product = Product::where('ulid', $validated['id'])->get()->first();
        $product->name = $validated['name'] ?? $product->name;
        $product->description = $validated['description'] ?? $product->description;
        $product->priceRub = $validated['priceRub'] ?? $product->priceRub;
        $product->available = $validated['available'] ?? $product->available;
        $product->productAmount = $validated['productAmount'] ?? $product->productAmount;
        $product->save();
        return response()->json(['updated_product' => $product], 200);
    }
    public function delete(APIProductsDeleteRequest $request)
    {
        $validated = $request->validated();
        if (!Product::where('ulid', $validated['id'])->exists())
            return response()->json(['message' => 'Trying to delete non-existent entity'], 403);
        Product::where('ulid', $validated['id'])->delete();
        if (!Product::where('ulid', $validated['id'])->exists())
            return response()->json(null, 200);
        else
            return response()->json(null, 400);
    }
    public function productsByNameSeller(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        $loadWithArray = null;
        if (array_key_exists('loadWith', $validated))
        {
            if (isset($validated['loadWith']))
            {
                $loadWithArray = explode(', ', $validated['loadWith']);
                for ($i = 0; $i < count($loadWithArray); $i++)
                    $loadWithArray[$i] = 'products.' . $loadWithArray[$i];
            }
        }
        $sellers = null;
        if ($loadWithArray != null)
        {
            $sellers = Seller::with(array_merge(['products'], $loadWithArray));
        }
        if (isset($sellers))
        {
            if (session('seller_id') != null)
                $sellers = $sellers->where('id', session('seller_id'));
            else
                $sellers = $sellers->where('nickname', 'like', '%' . $validated['sellerName'] . '%');
        }
        else
        {
            if (session('seller_id') != null)
                $sellers = Seller::where('id', session('seller_id'));
            else
                $sellers = Seller::where('nickname', 'like', '%' . $validated['sellerName'] . '%');
        }

        $sellers = $sellers->get();

        $products = new Collection();
        foreach ($sellers as $seller)
        {
            foreach ($seller->products as $product)
            {
                $result = strpos($product->name, $validated['productName']) !== false;
                if ($result != false)
                {
                    $products->add($product);
                }
            }
        }
        if ($products != null)
            return response()->json(['products' => $products->toArray()], 200);
        else
            return response()->json(['message' => 'Не было найдено товаров с таким именем'], 404);
    }
}
