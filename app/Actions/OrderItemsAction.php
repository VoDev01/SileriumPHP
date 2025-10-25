<?php
namespace App\Actions;

use App\Enum\SortOrder;

/**
 * Filter products by some column
 */
class OrderItemsAction 
{
    public static function orderItem(int $sortOrder)
    {
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC->value:
                $orderingType = 'name ASC';
                break;
            case SortOrder::NAME_DESC->value:
                $orderingType = 'name DESC';
                break;
            case SortOrder::POP_ASC->value:
                $orderingType = 'timesPurchased ASC';
                break;
            case SortOrder::POP_DESC->value:
                $orderingType = 'timesPurchased DESC';
                break;
            case SortOrder::PRICE_ASC->value:
                $orderingType = 'priceRub ASC';
                break;
            case SortOrder::PRICE_DESC->value:
                $orderingType = 'priceRub DESC';
                break;
        }
        return $orderingType;
    }
}