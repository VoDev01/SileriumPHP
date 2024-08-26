<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Enum\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Facades\ConvertCurrencyFacade as ConvertCurrency;

class ProductService 
{
    public function make(string $name, string $description, $priceRub, int $stockAmount, bool $available, int $subcategory_id, array $images = null)
    {
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], compact('name', 'description', 'priceRub', 'stockAmount', 'available', 'subcategory_id'), ['timesPurchased' => 0]);
        $productId = Product::insertGetId($insert);
        if($images != null)
        {
            for ($i=0; $i < $images->count(); $i++) { 
                DB::insert('INSERT INTO products_images (imagePath, product_id) VALUES (?, ?)', [$images[$i], $productId]);
            }
        }
        $product = Product::find($productId);
        return $product;
    }
    public function getFilterQuery(array $relationships = null, string $subcategory = "all", string $product = "", int $available = 1)
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
    public function getFilterProduct(string $relationships, string $subcategory = "all", string $product = null, int $available = 1)
    {
        $products = getFilterQuery($relationships, $subcategory, $product, $available)->get();
        return $products;
    }
}