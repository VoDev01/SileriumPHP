<?php
namespace App\Actions;

use Darryldecode\Cart\Facades\CartFacade as Cart;

class ChangeCartAmountAction
{
    /**
     * Changes product amount in cart by 1 in either direction (increases or decreases)
     *
     * @param integer $amount Initial amount
     * @param string $amountDir Direction to change amount (up for increase or down for decrease)
     * @param integer $productId
     * @param integer $userId
     * @return void
     */
    public static function changeAmount(int $amount, string $amountDir, int $productId, int $userId)
    {
        if ($amountDir === "up") {
            $amount++;
        } else  if ($amountDir === "down") { 
            $amount--;
        }
        $cartAmount = Cart::session($userId)->get($productId)->quantity;
        $totalAmount = $amount - $cartAmount;
        Cart::session($userId)->update($productId, array(
            'quantity' => $totalAmount
        ));
    }
}