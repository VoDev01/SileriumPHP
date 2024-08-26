<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\OrderProductsService;
use App\Facades\ProductCartServiceFacade as ProductCart;

class CatalogController extends Controller
{
    public function products(int $sortOrder = 1, int $available = 1, string $subcategory = "all", string $product = null)
    {
        if($subcategory == "all")
        {
            $query = Product::with('images')
            ->with('productSpecifications')
            ->where('name', 'like', $product == null ? "" : $product)
            ->orwhere('available', $available);
        }
        else
        {
            $query = Product::with('images')
            ->with('productSpecifications')
            ->where('subcategory_id', $subcategory)
            ->where('available', $available)
            ->orWhere('name', 'like', $product == null ? "" : $product);
        }
        $products = OrderProductsService::orderProduct($query, $sortOrder, 15);
        if(session('products_currency') == 'dol')
        {
            ProductCart::convertCurrency($products);
        }
        return view('catalog.products', ['products' => $products,
        'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 
        'subcategory' => $subcategory, 'product' => $product,
        'categories' => Category::all(), 'available' => $available]);
    }
    public function filterProducts(Request $request)
    {
        session(['loadWith' => $request->loadWith]);
        return redirect()->route('allproducts', ['sortOrder' => $request->sortOrder, 
        'available' => $request->available, 'subcategory' => $request->subcategory, 
        'product' => $request->product]);
    }
    public function product(int $productId)
    {
        $product = Product::where('id', $productId)->with('productSpecifications')->with('images')->with('reviews')->get()->first(); 
        $reviews = $product->reviews()->with('user')->paginate(5);
        return view('catalog.product', ['product' => $product, 'reviews' => $reviews]);
    }
    public function rubCurrency()
    {
        session(['products_currency' => 'rub']);
        session(['currency_symb' => '&#8381;']);
        return redirect()->route('allproducts');
    }
    public function dolCurrency()
    {
        session(['products_currency' => 'dol']);
        session(['currency_symb' => '&#36;']);
        return redirect()->route('allproducts');
    }
}
