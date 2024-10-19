<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\API\APIProductsSearchRequest;

class ProductsAdminPanelController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15); 
        return view('admin.products.index', ['products' => $products]);
    }
    public function update(int $id)
    {
        $product = Product::find($id);
        $categories = Category::with('subcategories')->get();
        return view('admin.products.update', ['product' => $product, 'categories' => $categories]);
    }
    public function postUpdatedProduct(Request $request)
    {
        $response = Http::put('/api/v1/products/update/' + $request->id);
        if($response->ok())
        {
            return redirect()->route('products_index');
        }
    }
    public function delete(int $product_id)
    {
        $product = Product::find($product_id);
        return view("admin.products.delete", ['product' => $product]);
    }
    public function postDeletedProduct(Request $request)
    {
        $response = Http::delete('/api/v1/products/delete/' + $request->id);
        if($response->ok())
        {
            return view("products_index");
        }
    }
    public function categories(Request $request, int $id)
    {
        if($request->ajax())
        {
            $subcategories = Subcategory::where('category_id', $id)->get();
            return response()->json(['subcategories' => $subcategories]);
        }
    }
    public function productJson(APIProductsSearchRequest $request)
    {
        if($request->ajax())
        {
            $validated = $request->validated();
            $products = Http::get('/api/v1/products/by_names_seller/' . $validated['seller_nickname'] . '/' . $validated['product_name'])['product'];
            return response()->json(['product' => $products]);
        }
    }
    public function productReviews()
    {
        return view('admin.products.reviews');
    }
    public function searchProductReviews(APIProductsSearchRequest $request)
    {
        if($request->ajax())
        {
            $validated = $request->validated();
            $products = Http::get('/api/v1/products/by_names_seller/' . $validated['seller_nickname'] . '/' . $validated['product_name'] . '/reviews')['product'];
            return response()->json(['reviews' => $products->reviews]);
        }
    }

}
