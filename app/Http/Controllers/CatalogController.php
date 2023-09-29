<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Enum\SortOrder;
use App\Facades\Currency as Currency;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;

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
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC->value:
                $products = $query->orderBy('name', 'asc')->paginate(15);
                break;
            case SortOrder::NAME_DESC->value:
                $products = $query->orderBy('name', 'desc')->paginate(15);
                break;
            case SortOrder::POP_ASC->value:
                $products = $query->orderBy('timesPurchased', 'asc')->paginate(15);
                break;
            case SortOrder::POP_DESC->value:
                $products = $query->orderBy('timesPurchased', 'desc')->paginate(15);
                break;
            case SortOrder::PRICE_ASC->value:
                $products = $query->orderBy('priceRub', 'asc')->paginate(15);
                break;
            case SortOrder::PRICE_DESC->value:
                $products = $query->orderBy('priceRub', 'desc')->paginate(15);
                break;
        }
        if(session('products_currency') == 'dol')
        {
            foreach ($products as $product) {
                $product->priceRub = round(Currency::convertToDol($product->priceRub), 1);
            }
        }
        return view('catalog.products', ['products' => $products,
        'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 
        'subcategory' => $subcategory, 'product' => $product,
        'categories' => Category::all(), 'available' => $available]);
    }
    public function filterProducts(Request $request)
    {
        return redirect()->route('allproducts', ['sortOrder' => $request->sort_order, 
        'available' => $request->available, 'subcategory' => $request->subcategory, 
        'product' => $request->product]);
    }
    public function product(Product $product)
    {
        $productEag = Product::where('id', $product->id)->with('productSpecifications')->with('images')->with('reviews')->get()->first(); 
        $reviews = $productEag->reviews()->with('user')->paginate(5);
        return view('catalog.product', ['product' => $productEag, 'reviews' => $reviews]);
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
        $product = Product::find($request->product_id);
        $product->amount -= $validated['amount'];
        $product->timesPurchased += $validated['amount'];
        if($product->amount <= 0)
            $product->available = false;
        Cart::session($user->id)->add(
            $product->id, 
            $product->name, 
            session('products_currency') == "dol" ? round(Currency::convertToDol($product->priceRub), 1) : $product->priceRub,
            $validated['amount'])->associate('Product', 'App\Models');
        return redirect()->route('allproducts');
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
