<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Facades\ConvertCurrencyFacade as ConvertCurrency;

class ProductCartService
{
    public function convertCurrency($products)
    {
        foreach ($products as $product)
        {
            $product->priceRub = round(ConvertCurrency::convertToDol($product->priceRub), 1);
        }
    }
    public function addProductToCart(User $user, int $productId, int $amount)
    {
        $product = Product::where('id', $productId)->get()->first();
        // $product->productAmount -= $amount;
        // $product->timesPurchased += $amount;
        // if ($product->productAmount <= 0)
        //     $product->available = false;
        Cart::session($user->id)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => session('products_currency') === "dol" ? round(ConvertCurrency::convertToDol($product->priceRub), 1) : $product->priceRub,
            'attributes' => array(),
            'quantity' => $amount,
            'associatedModel' => $product
        ));
    }
}
