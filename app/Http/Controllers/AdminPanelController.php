<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function home()
    {
        return view("admin.home");
    }
    public function products_index()
    {
        $products = Product::paginate(15);
        $subcategories = Subcategory::all();
        return view('admin.products.index', ['products' => $products, 'subcategories' => $subcategories]);
    }
    public function create_product()
    {
        return view('admin.create_product');
    }
    public function post_product(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'priceRub' => $request->priceRub,
            'stockAmount' => $request->stockAmount,
            'available' => $request->available,
            'subcategory' => $request->subcategory,
        ]);
        $product->save();
        return redirect()->route('products_index');
    }
    public function update_product(int $id)
    {
        $product = Product::find($id);
        return view('admin.update_product', ['product' => $product]);
    }
    public function post_updated_product(Request $request)
    {
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->decsription = $request->description;
        $product->priceRub = $request->priceRub;
        $product->stockAmount = $request->stockAmount;
        $product->available = $request->available;
        $product->subcategory = $request->subcategory;
        $product->save();
        return view('products_index');
    }
    public function delete_product(int $id)
    {
        $product = Product::find($id);
        return view("admin.delete_product", ['product' => $product]);
    }
    public function post_deleted_product(Request $request)
    {
        Product::destroy($request->id);
        return view("products_index");
    }
}
