<?php
namespace App\Actions;

use App\Enum\SortOrder;

class OrderItemsAction 
{
    public static function orderItem(int $sortOrder)
    {
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC->value:
                $items = 'ORDER BY name ASC';
                break;
            case SortOrder::NAME_DESC->value:
                $items = 'ORDER BY name desc';
                break;
            case SortOrder::POP_ASC->value:
                $items = 'ORDER BY timesPurchased asc';
                break;
            case SortOrder::POP_DESC->value:
                $items = 'ORDER BY timesPurchased desc';
                break;
            case SortOrder::PRICE_ASC->value:
                $items = 'ORDER BY priceRub asc';
                break;
            case SortOrder::PRICE_DESC->value:
                $items = 'ORDER BY priceRub desc';
                break;
        }
        return $items;
    }
}