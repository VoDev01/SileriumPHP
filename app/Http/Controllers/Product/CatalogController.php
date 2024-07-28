<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;
use App\Enum\OrderStatus;
use App\Enum\SortOrder;
use App\Facades\ConvertCurrency;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\OrderItemsFacade as OrderItems;
use App\Facades\ProductServiceFacade as ProductService;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CatalogController extends Controller
{
    public function products(int $sortOrder = 1, int $available = 1, string $subcategory = "all", string $product = "")
    {
        $relationships = null;
        if(session('loadWith') != null)
            $relationships = explode(', ', session('loadWith'));
        $query = ProductService::getFilterQuery($relationships, $subcategory, $product, $available);
        $products = OrderItems::orderItem($query, $sortOrder, 15);
        if(session('products_currency') == 'dol')
        {
            ProductService::convertCurrency($products);
        }
        return view('catalog.products', ['products' => $products,
        'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 
        'subcategory' => $subcategory, 'product' => $product,
        'categories' => Category::all(), 'available' => $available]);
    }
    public function filterProducts(Request $request)
    {
        return redirect()->route('allproducts', ['sortOrder' => $request->sortOrder, 
        'available' => $request->available, 'subcategory' => $request->subcategory, 
        'product' => $request->name])->with(['loadWith' => $request->loadWith]);
    }
    public function product(int $productId)
    {
        $product = Product::where('id', $productId)->with(['productSpecifications', 'images', 'reviews'])->get()->first(); 
        $reviews = $product->reviews()->with('user')->paginate(5);
        return view('catalog.product', ['product' => $product, 'reviews' => $reviews]);
    }
    public function addToCart(Product $product)
    {
        return view('catalog.addtocart', ['product' => $product]);
    }
    public function postCart(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required'
        ]);
        $user = User::find(Auth::id());
        if($user->homeAdress === null || $user->city === null)
            return back()->withErrors([
                'homeAdress' => 'Заполните данные местоположения перед оформлением заказов'
            ]);
        ProductService::addToCart($user, (int)$validated['amount']);
        return redirect()->route('allproducts');
    }
    public function rubCurrency()
    {
        session(['products_currency' => 'rub']);
        return redirect()->route('allproducts');
    }
    public function dolCurrency()
    {
        session(['products_currency' => 'dol']);
        return redirect()->route('allproducts');
    }
}
