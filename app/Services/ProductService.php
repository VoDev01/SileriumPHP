<?php

namespace App\Services;

use App\Actions\OrderItemsAction;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public static function make(array $validatedInput, array $images = null, array $select)
    {
        $insert = [
            'ulid' => Str::ulid()->toBase32(),
            'name' => $validatedInput['name'],
            'description' => $validatedInput['description'],
            'priceRub' => $validatedInput['priceRub'],
            'productAmount' => $validatedInput['productAmount'],
            'available' => $validatedInput['available'],
            'subcategory_id' => $validatedInput['subcategory_id'],
            'timesPurchased' => 0,
            'seller_id' => $validatedInput['seller_id']
        ];
        $productId = Product::insertGetId($insert);
        if ($images != null)
        {
            for ($i = 0; $i < count($images); $i++)
            {
                DB::insert('INSERT INTO products_images (imagePath, product_id) VALUES (?, ?)', [$images[$i], $productId]);
            }
        }
        $product = DB::table('products')->select($select)->where('id', $productId)->get()->first();
        return $product;
    }
    public static function getProductsFilterQuery(
        int $sortOrder,
        array $relationships = null,
        array|string $select = "name",
        string $subcategory = "all",
        string $name = "",
        int $available = 1,
        int $items = 15
    )
    {
        if ($relationships != null)
        {
            $products = Product::select($select)
                ->with($relationships)
                ->orderByRaw(DB::raw(OrderItemsAction::orderItem($sortOrder)))
                ->paginate($items, $select);
        }
        else
        {
            if ($subcategory == "all" && $name == "")
            {
                $products = DB::table('products')
                    ->where('available', $available)
                    ->orderByRaw(DB::raw(OrderItemsAction::orderItem($sortOrder)))->paginate($items, $select);
            }
            else if ($subcategory == "all")
            {
                $products = DB::table('products')
                    ->where('name', 'like', '%' . $name . '%')
                    ->where('available', $available)
                    ->orderByRaw(DB::raw(OrderItemsAction::orderItem($sortOrder)))
                    ->paginate($items, $select);
            }
            else
            {
                $products = DB::table('products')
                    ->where('name', 'like', '%' . $name . '%')
                    ->where('subcategory_id', $subcategory)
                    ->where('available', $available)
                    ->orderByRaw(DB::raw(OrderItemsAction::orderItem($sortOrder)))
                    ->paginate($items, $select);
            }
        }
        return $products;
    }
    public static function getFilterProducts(array $relationships = null, string $subcategory = "all", string $product = "", int $available = 1)
    {
        $products = getProductsFilterQuery($relationships, $subcategory, $product, $available)->get();
        return $products;
    }
}
