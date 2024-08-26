<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MakeProductService 
{
    public static function make(string $name, string $description, $priceRub, int $stockAmount, bool $available, int $subcategory_id, array $images = null)
    {
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], compact($name, $description, $priceRub, $stockAmount, $available, $subcategory_id));
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
}