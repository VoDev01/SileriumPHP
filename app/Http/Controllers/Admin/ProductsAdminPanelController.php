<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class ProductsAdminPanelController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);
        return view('admin.products.index', ['products' => $products]);
    }
    public function create()
    {
        $categories = Category::with('subcategories')->get();
        return view('admin.products.create', ['categories' => $categories]);
    }
    public function postProduct(Request $request)
    {
        $response = Http::post('/api/v1/products/create');
        if($response->ok())
        {
            return redirect()->route('products_index');
        }
    }
    public function update(int $id)
    {
        $product = Product::find($id);
        $categories = Category::with('subcategories')->get();
        return view('admin.products.update', ['product' => $product, 'categories' => $categories]);
    }
    public function postUpdatedProduct(Request $request)
    {
        $response = Http::post('/api/v1/products/update/' + $request->id);
        if($response->ok())
        {
            return redirect()->route('products_index');
        }
    }
    public function delete(int $id)
    {
        $product = Product::find($id);
        return view("admin.products.delete", ['product' => $product]);
    }
    public function postDeletedProduct(Request $request)
    {
        $response = Http::post('/api/v1/products/delete/' + $request->id);
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
    public function productJson(Request $request, int $id, string $name = null)
    {
        if($request->ajax())
        {
            $response = Http::get('/api/v1/products/by_nameid/' . $id . '/' . $name)['product'];
            return response()->json(['product' => $response]);
        }
    }
}
