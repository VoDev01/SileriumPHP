<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
<<<<<<< Updated upstream
use App\Models\User;
use App\Services\OrderProductsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
=======
use Illuminate\Http\Request;
use App\Facades\OrderItemsFacade as OrderItems;
use App\Facades\ProductServiceFacade as ProductService;
use App\Facades\ProductCartServiceFacade as ProductCart;
>>>>>>> Stashed changes

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
<<<<<<< Updated upstream
            foreach ($products as $product) {
                $product->priceRub = round(ConvertCurrency::convertToDol($product->priceRub), 1);
            }
=======
            ProductCart::convertCurrency($products);
>>>>>>> Stashed changes
        }
        return view('catalog.products', ['products' => $products,
        'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 
        'subcategory' => $subcategory, 'product' => $product,
        'categories' => Category::all(), 'available' => $available]);
    }
    public function filterProducts(Request $request)
    {
<<<<<<< Updated upstream
        return redirect()->route('allproducts', ['sortOrder' => $request->sort_order, 
=======
        session(['loadWith' => $request->loadWith]);
        return redirect()->route('allproducts', ['sortOrder' => $request->sortOrder, 
>>>>>>> Stashed changes
        'available' => $request->available, 'subcategory' => $request->subcategory, 
        'product' => $request->product]);
    }
    public function product(int $productId)
    {
        $product = Product::where('id', $productId)->with('productSpecifications')->with('images')->with('reviews')->get()->first(); 
        $reviews = $product->reviews()->with('user')->paginate(5);
        return view('catalog.product', ['product' => $product, 'reviews' => $reviews]);
    }
<<<<<<< Updated upstream
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
            session('products_currency') == "dol" ? round(ConvertCurrency::convertToDol($product->priceRub), 1) : $product->priceRub,
            $validated['amount'])->associate('Product', 'App\Models');
        return redirect()->route('allproducts');
    }
=======
>>>>>>> Stashed changes
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
