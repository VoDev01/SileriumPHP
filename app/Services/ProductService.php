<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService 
{
    public static function make(array $validatedInput, array $images = null)
    {
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], [
            'name' => $validatedInput['name'], 
            'description' => $validatedInput['description'], 
            'priceRub' => $validatedInput['priceRub'], 
            'stockAmount' => $validatedInput['stockAmount'], 
            'available' => $validatedInput['available'], 
            'subcategory_id' => $validatedInput['subcategory_id'], 
            'timesPurchased' => 0]);
        $productId = Product::insertGetId($insert);
        if($images != null)
        {
            for ($i=0; $i < count($images); $i++) { 
                DB::insert('INSERT INTO products_images (imagePath, product_id) VALUES (?, ?)', [$images[$i], $productId]);
            }
        }
        $product = Product::find($productId);
        return $product;
    }
    private static function getFilterQuery(array $relationships = null, string $subcategory = "all", string $product = "", int $available = 1)
    {
        if($relationships != null)
        {
            $query = Product::with($relationships)->get()->toQuery();
        }
        else
        {
            $query = Product::get()->toQuery();
        }
        if($subcategory == "all" && $product == "")
        {
            $query = $query->where('available', $available);
        }
        else if ($subcategory == "all")
        {
            $query = $query->where('name', 'like', '%'.$product.'%')
            ->where('available', $available);
        }
        else
        {
            $query = $query->where('name', 'like', '%'.$product.'%')
            ->where('subcategory_id', $subcategory)
            ->where('available', $available);
        }
        return $query;
    }
    public static function getFilterProduct(array $relationships = null, string $subcategory = "all", string $product = "", int $available = 1)
    {
        $products = getFilterQuery($relationships, $subcategory, $product, $available)->get();
        return $products;
    }
}