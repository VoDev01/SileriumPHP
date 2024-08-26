<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\ChangeCartAmount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Facades\ProductCartServiceFacade as ProductCart;

class UserCartController extends Controller
{
    
    public function cart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.cart', ['products' => $products]);
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
        ProductCart::addProductToCart($user, $request, (int)$validated['amount']);
        return redirect()->route('allproducts');
    }
    public function changeAmount(Request $request)
    {
        ChangeCartAmount::changeAmount($request->amount, $request->amountChange, $request->productId, Auth::id());
        return redirect()->route('cart');
    }
    public function filterCart(Request $request)
    {
        $orders = Order::all()->where('orderStatus', $request->orderStatus);
        return redirect()->route('/user/cart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::session(Auth::id())->remove($request->productId);
        return redirect()->route('cart');
    }
}
