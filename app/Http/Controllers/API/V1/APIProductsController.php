<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\ApiFilters\V1\ProductFilter;
use App\Http\Controllers\Controller;
use App\Services\MakeProductService;
use App\Http\Resources\V1\ProductsResource;
use App\Http\Resources\V1\ProductsCollection;

class APIProductsController extends Controller
{
    public function index(int $items_per_page)
    {
        $products = Product::paginate($items_per_page);
        return response()->json(['response' => $products]);
    }
    public function show(int $id)
    {
        $products = Product::find($id);
        return response()->json(['response' => $products]);
    }
    public function create(Request $request)
    {
        MakeProductService::make($request->name, $request->description, $request->priceRub, $request->stockAmount, $request->avilable, $request->subcategory_id);
        return response()->json(null, 200);
    }
    public function update(int $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->decsription = $request->description;
        $product->priceRub = $request->priceRub;
        $product->stockAmount = $request->stockAmount;
        $product->available = $request->available;
        $product->subcategory = $request->subcategory;
        $product->save();
        return response()->json(null, 200);
    }
    public function delete(int $id)
    {
        Product::destroy($id);
        return response()->json(null, 200);
    }
    public function product_by_nameid(int $id, string $name = null)
    {
        $product = Product::where('id', $id)->orWhere('name', $name)->get()->first();
        return response()->json(['product' => $product]);
    }
}
