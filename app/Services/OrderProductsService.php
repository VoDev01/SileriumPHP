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
                $products = $query->orderBy('name', 'asc')->paginate($onPage);
                break;
            case SortOrder::NAME_DESC->value:
                $products = $query->orderBy('name', 'desc')->paginate($onPage);
                break;
            case SortOrder::POP_ASC->value:
                $products = $query->orderBy('timesPurchased', 'asc')->paginate($onPage);
                break;
            case SortOrder::POP_DESC->value:
                $products = $query->orderBy('timesPurchased', 'desc')->paginate($onPage);
                break;
            case SortOrder::PRICE_ASC->value:
                $products = $query->orderBy('priceRub', 'asc')->paginate($onPage);
                break;
            case SortOrder::PRICE_DESC->value:
                $products = $query->orderBy('priceRub', 'desc')->paginate($onPage);
                break;
        }
        return $products;
    }
}