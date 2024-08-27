<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\APIProductsRequest;

class APIProductsController extends Controller
{
    public function index(int $itemsPerPage = 15)
    {
        $products = Product::orderBy('id')->paginate($itemsPerPage);
        return response()->json($products, 200);
    }
    public function show(int $id)
    {
        $product = Product::where('id', $id)->orderBy('id')->get();
        if($product != null)
            return response()->json($product, 200);
        else
            return response()->json(['message' => 'No product was found with this id'], 400);
    }
    public function create(APIProductsRequest $request)
    {
        $validated = $request->validated();
        $product = ProductService::make($validated);
        return response()->json(['Url' => 'https://silerium.com/catalog/product/' . $product->ulid], 200);
    }
    public function update(APIProductsRequest $request)
    {
        $validated = $request->validated();
        Product::where('id', $request->id)->orderBy('id')->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'priceRub' => $validated['priceRub'],
            'stockAmount' => $validated['stockAmount'],
            'available' => $validated['available'],
            'subcategory_id' =>  $validated['subcategory_id']
        ]);
        return response()->json(['Url' => 'https://silerium.com/catalog/product/' . $validated['name']], 200);
    }
    public function delete(Request $request)
    {
        if(!Product::where('id', $request->id)->exists())
            return response()->json(['message' => 'Trying to delete non-existent entity'], 403);
        Product::destroy($request->id);
        if(!Product::where('id', $request->id)->exists())
            return response()->json(null, 200);
        else
            return response()->json(null, 400);

    }
    public function productsByNameId(string $name, int $id = null)
    {
        $products = Product::where('name', 'like', '%'.$name.'%')->orWhere('id', $id)->orderBy('id')->get();
        if($products != null)
            return response()->json($products, 200);
        else
            return response()->json(['message' => 'No products were found with this name or id'], 400);
    }
}
