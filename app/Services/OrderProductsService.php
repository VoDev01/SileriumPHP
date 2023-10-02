<?php
namespace App\Services;

use App\Enum\SortOrder;

class OrderProductsService 
{
    public static function orderProduct($query, int $sortOrder, int $onPage)
    {
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
        return $products;
    }
}