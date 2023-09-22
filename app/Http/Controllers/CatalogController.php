<?php

namespace App\Http\Controllers;

use App\Enum\SortOrder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

class CatalogController extends Controller
{
    public function products(SortOrder $sortOrder = SortOrder::NAME_DESC, int $subcategory = 1, string $product = null, bool $available = true)
    {
        if($product != null)
        {
            $query = Product::where('subcategory_id', $subcategory)
            ->where('name', 'like', $product)
            ->where('available', $available);
        }
        else
        {
            $query = Product::where('subcategory_id', $subcategory)
            ->where('available', $available);
        }
        switch($sortOrder)
        {
            case SortOrder::NAME_ASC;
                $products = $query->orderBy('name', 'asc')->get();
                break;
            case SortOrder::NAME_DESC;
                $products = $query->orderBy('name', 'desc')->get();
                break;
            case SortOrder::POP_ASC;
                $products = $query->orderBy('timesPurchased', 'asc')->get();
                break;
            case SortOrder::POP_DESC;
                $products = $query->orderBy('timesPurchased', 'desc')->get();
                break;
            case SortOrder::PRICE_ASC;
                $products = $query->orderBy('priceRub', 'asc')->get();
                break;
            case SortOrder::PRICE_DESC;
                $products = $query->orderBy('priceRub', 'desc')->get();
                break;
        }
        return view('catalog.products', ['products' => $products, 'sortOrder' => $sortOrder, 'subcategories' => Subcategory::all(), 'categories' => Category::all()]);
    }
    public function product(Product $product)
    {
        return view('catalog.product', ['product' => $product]);
    }
    
}
