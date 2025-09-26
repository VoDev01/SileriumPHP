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
                $orderingType = 'name desc';
                break;
            case SortOrder::POP_ASC->value:
                $orderingType = 'timesPurchased asc';
                break;
            case SortOrder::POP_DESC->value:
                $orderingType = 'timesPurchased desc';
                break;
            case SortOrder::PRICE_ASC->value:
                $orderingType = 'priceRub asc';
                break;
            case SortOrder::PRICE_DESC->value:
                $orderingType = 'priceRub desc';
                break;
        }
        return $orderingType;
    }
}