<?php

namespace App\Http\Controllers;

use App\Enum\SortOrder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function products(SortOrder $sortOrder = SortOrder::NAME_DESC, int $subcategory = 1, string $product = "", bool $available = true)
    {
        $query = Product::where('subcategory_id', $subcategory)
        ->where('name', 'like', $product)
        ->where('available', $available);
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC;
                $products = $query->orderBy('name', 'asc')->get();
                break;
            case SortOrder::NAME_DESC;
                $products = $query->orderBy('name', 'desc')->get();
                break;
            case SortOrder::POP_ASC;
                $products = $query->orderBy('timesPurchased', 'asc')->get();
                break;
            case SortOrder::POP_DESC;
                $products = $query->orderBy('timesPurchased', 'desc')->get();
                break;
            case SortOrder::PRICE_ASC;
                $products = $query->orderBy('priceRub', 'asc')->get();
                break;
            case SortOrder::PRICE_DESC;
                $products = $query->orderBy('priceRub', 'desc')->get();
                break;
        }
        return view('catalog.products', ['products' => $products, 'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 'categories' => Category::all()]);
    }
    public function filterProducts(Request $request)
    {
        return redirect()->route('allproducts', ['sortOrder' => $request->sortOrder, 'subcategory' => $request->subcategory, 
        'product' => $request->product, 'available' => $request->available]);
    }
    public function product(Product $product)
    {
        return view('catalog.product', ['product' => $product]);
    }
    public function addToCart(Product $product)
    {
        return view('addtocart', ['product' => $product]);
    }
    public function postCart(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required'
        ]);
        $totalPrice = $request->amount * Product::find($request->product_id)->priceRub;
        return redirect()->route('allproducts');
    }
}
