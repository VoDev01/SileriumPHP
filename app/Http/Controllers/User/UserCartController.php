<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Actions\ChangeCartAmountAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Facades\ProductCartServiceFacade as ProductCart;
use App\Http\Requests\Cart\AddProductToCartRequest;
use App\Http\Requests\Cart\ChangeProductAmountRequest;

class UserCartController extends Controller
{
    
    public function cart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.cart', ['products' => $products, 'user_id' => Auth::user()->id]);
    }
    public function addToCart(int $productId)
    {
        $product = Product::find($productId);
        return view('catalog.addtocart', ['product' => $product]);
    }
    public function postCart(AddProductToCartRequest $request)
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        if($user->homeAdress === null || $user->city === null)
            return back()->withErrors([
                'homeAdress' => 'Заполните данные местоположения перед оформлением заказов'
            ]);
        ProductCart::addProductToCart($user, $validated['productId'], $validated['amount']);
        return redirect()->route('cart');
    }
    public function changeAmount(ChangeProductAmountRequest $request)
    {
        $validated = $request->validated();
        ChangeCartAmountAction::changeAmount($validated['amount'], $validated['amountChange'], $validated['productId'], Auth::id());
        return redirect()->route('cart');
    }
    public function filterCart(Request $request)
    {
        $orders = Order::all()->where('status', $request->status);
        return redirect()->route('/user/cart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::session(Auth::id())->remove($request->productId);
        return redirect()->route('cart');
    }
}
