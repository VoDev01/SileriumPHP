<?php

namespace App\Repositories;

use App\Actions\OrderItemsAction;
use App\Enum\SortOrder;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function index(int $itemsPerPage = 15)
    {
        $products = Product::orderBy('id')->paginate($itemsPerPage);
        return $products;
    }

    public function show(string $id)
    {
        $product = DB::table('products')
            ->selectRaw('products.*,
            sellers.id as seller_id,
            sellers.ulid as seller_ulid,
            sellers.nickname as nickname,
            GROUP_CONCAT(ps.specification SEPARATOR \', \') AS specs,
            GROUP_CONCAT(ps.name SEPARATOR \', \') AS specs_names,
            GROUP_CONCAT(product_images.imagePath SEPARATOR \', \') as images')
            ->joinSub(
                DB::table('products_specifications')
                    ->select('product_id', 'product_specifications.name', 'product_specifications.specification')
                    ->rightJoin('product_specifications', 'products_specifications.specification_id', '=', 'product_specifications.id'),
                'ps',
                'products.id',
                '=',
                'ps.product_id',
                'left'
            )
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->where('products.ulid', $id)
            ->groupBy(
                'products.ulid',
                'products.id',
                'products.name',
                'products.description',
                'products.priceRub',
                'products.productAmount',
                'products.available',
                'products.subcategory_id',
                'products.seller_id',
                'products.timesPurchased',
                'sellers.id',
                'sellers.ulid',
                'sellers.nickname'
            )
            ->get()->first();
        return $product;
    }
    /**
     * Create product and select only needed
     *
     * @param array $validatedInput
     * @param array|null $images
     * @param array $select
     * @return mixed|null
     */
    public function create(array $validatedInput, array $images = null, array $select)
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
        if ($images !== null)
        {
            for ($i = 0; $i < count($images); $i++)
            {
                DB::insert('INSERT INTO products_images (imagePath, product_id) VALUES (?, ?)', [$images[$i], $productId]);
            }
        }
        $product = DB::table('products')->select($select)->where('id', $productId)->get()->first();
        return $product;
    }

    public function update(array $validated, array $images = null)
    {
        $validated['ulid'] = $validated['id'];
        unset($validated['id']);
        $validated = array_filter($validated);
        Product::where('ulid', $validated['ulid'])->update(array_filter($validated, fn($elem) => $elem !== 'ulid' && $elem !== null));
        if ($images !== null)
        {
            for ($i = 0; $i < count($images); $i++)
            {
                DB::update('UPDATE products_images SET imagePath = ? WHERE product_id = ?', [$images[$i], $validated['ulid']]);
            }
        }
        $product = DB::table('products')->where('ulid', $validated['ulid'])->get()->first();
        return $product;
    }

    public function delete(string $id)
    {
        if (!Product::where('ulid', $id)->exists())
            return false;

        Product::where('ulid', $id)->delete();

        return true;
    }
}
