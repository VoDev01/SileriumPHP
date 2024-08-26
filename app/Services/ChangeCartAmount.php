<?php
namespace App\Services;

use Darryldecode\Cart\Facades\CartFacade as Cart;

class ChangeCartAmount
{
    public static function changeAmount(int $amount, string $amountChange, int $productId, int $userId)
    {
        if ($amountChange == "up") {
            $amount++;
        } else {
            $amount--;
        }
        $cartAmount = Cart::session($userId)->get($productId)->quantity;
        $totalAmount = $amount - $cartAmount;
        Cart::session($userId)->update($productId, array(
            'quantity' => $totalAmount
        ));
    }
}