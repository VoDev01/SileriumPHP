<?php
namespace App\Services;

use App\Enum\SortOrder;

class OrderItemsService 
{
    public function orderItem($query, int $sortOrder, int $onPage)
    {
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC->value:
                $items = $query->orderBy('name', 'asc')->paginate($onPage);
                break;
            case SortOrder::NAME_DESC->value:
                $items = $query->orderBy('name', 'desc')->paginate($onPage);
                break;
            case SortOrder::POP_ASC->value:
                $items = $query->orderBy('timesPurchased', 'asc')->paginate($onPage);
                break;
            case SortOrder::POP_DESC->value:
                $items = $query->orderBy('timesPurchased', 'desc')->paginate($onPage);
                break;
            case SortOrder::PRICE_ASC->value:
                $items = $query->orderBy('priceRub', 'asc')->paginate($onPage);
                break;
            case SortOrder::PRICE_DESC->value:
                $items = $query->orderBy('priceRub', 'desc')->paginate($onPage);
                break;
        }
        return $items;
    }
}