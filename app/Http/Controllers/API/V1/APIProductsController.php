<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsDeleteRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use Illuminate\Support\Carbon;

class APIProductsController extends Controller
{
    public function index(int $itemsPerPage = 15)
    {
        $products = Product::orderBy('id')->paginate($itemsPerPage);
        return response()->json($products->toArray(), 200);
    }
    public function show(int $id)
    {
        $product = Product::where('id', $id)->orderBy('id')->get();
        if ($product != null)
            return response()->json(['product' => $product], 200);
        else
            return response()->json(['message' => 'No product was found with this id'], 400);
    }
    public function create(APIProductsCreateRequest $request)
    {
        $validated = $request->validated();
        $product = ProductService::make($validated, null, ['*']);
        return response()->json(['Url' => env('APP_URL') . '/catalog/product/' . $product->ulid], 200);
    }
    public function update(APIProductsUpdateRequest $request)
    {
        $validated = $request->validated();
        $product = Product::where('ulid', $validated['id'])->get()->first();
        $product->name = $validated['name'] ?? $product->name;
        $product->description = $validated['description'] ?? $product->description;
        $product->priceRub = $validated['priceRub'] ?? $product->priceRub;
        $product->available = $validated['available'] ?? $product->available;
        $product->productAmount = $validated['productAmount'] ?? $product->productAmount;
        $product->save();
        return response()->json(['updated_product' => $product], 200);
    }
    public function delete(APIProductsDeleteRequest $request)
    {
        $validated = $request->validated();
        if (!Product::where('ulid', $validated['id'])->exists())
            return response()->json(['message' => 'Trying to delete non-existent entity'], 403);
        Product::where('ulid', $validated['id'])->delete();
        if (!Product::where('ulid', $validated['id'])->exists())
            return response()->json(null, 200);
        else
            return response()->json(null, 400);
    }
    public function productsByNameSeller(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        $loadWithArray = null;
        if (array_key_exists('loadWith', $validated))
        {
            if (isset($validated['loadWith']))
            {
                $loadWithArray = explode(', ', $validated['loadWith']);
                for ($i = 0; $i < count($loadWithArray); $i++)
                    $loadWithArray[$i] = 'products.' . $loadWithArray[$i];
            }
        }
        $sellers = null;
        if ($loadWithArray != null)
        {
            $sellers = Seller::with(array_merge(['products'], $loadWithArray));
        }
        if (isset($sellers))
        {
            if (session('seller_id') != null)
                $sellers = $sellers->where('id', session('seller_id'));
            else
                $sellers = $sellers->where('nickname', 'like', '%' . $validated['sellerName'] . '%');
        }
        else
        {
            if (session('seller_id') != null)
                $sellers = Seller::where('id', session('seller_id'));
            else
                $sellers = Seller::where('nickname', 'like', '%' . $validated['sellerName'] . '%');
        }

        $sellers = $sellers->get();

        $products = new Collection();
        foreach ($sellers as $seller)
        {
            foreach ($seller->products as $product)
            {
                $result = stripos($product->name, $validated['productName']) !== false;
                if ($result != false)
                {
                    $products->add($product);
                }
            }
        }
        if ($products != null)
            return response()->json(['products' => $products->toArray()], 200);
        else
            return response()->json(['message' => 'Не было найдено товаров с таким именем'], 404);
    }
    public function productsProfitBetweenTime(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        $products = DB::table('products')
            ->select('products.ulid, products.id, SUM(op.productsPrice) as profit, ')
            ->leftJoinSub(
                DB::table('orders_products')->whereIn('order_id', function($query, $validated){
                    $query->select('ulid')->from('orders')
                    ->whereBetween('orderDate', [$validated['lowerDateTime'], $validated['upperDateTime']]);
                }), 'op', 'products.id', '=', 'op.product_id'
            )
            ->where('name', 'like', '%'.$validated['productName'].'%')
            ->groupBy('products.ulid', 'products.id')
            ->get();
        if($products != null)
            return response()->json(['products' => $products->toArray()], 200);
        else
            return response()->json(['message' => 'За данный отрезок времени не найдено доходов.']);
    }
    public function productsYearlyConsumptionByMonts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        $consumption = DB::select('SELECT cons.product_id, cons.consumptionAmount, cons.cDate FROM (
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-01-01 AND \''. $validated['year'] . '-02-01\'
            ORDER BY orders.orderDate DESC
            GROUP BY product_id

            UNION ALL

            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-02-01 AND \''. $validated['year'] . '-03-01\'
            ORDER BY orders.orderDate DESC LIMIT 1
            GROUP BY product_id

            UNION ALL

            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-03-01 AND \''. $validated['year'] . '-04-01\'
            ORDER BY orders.orderDate DESC LIMIT 1
            GROUP BY product_id

            UNION ALL

            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-05-01 AND \''. $validated['year'] . '-06-01\'
            ORDER BY orders.orderDate DESC LIMIT 1
            GROUP BY product_id

            UNION ALL
            
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-07-01 AND \''. $validated['year'] . '-08-01\'
            ORDER BY orders.orderDate DESC
            GROUP BY product_id
            
            UNION ALL
            
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-09-01 AND \''. $validated['year'] . '-10-01\'
            ORDER BY orders.orderDate DESC LIMIT 1
            GROUP BY product_id
            
            UNION ALL
            
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumptionAmount, orders.orderDate as cDate FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            WHERE orders.orderDate BETWEEN \'' . $validated['year'] . '-11-01 AND \''. $validated['year'] . '-12-01\'
            ORDER BY orders.orderDate DESC LIMIT 1
            GROUP BY product_id
        ) as cons');
        if($consumption != null)
            return response()->json(['consumption' => $consumption], 200);
        else
            return response()->json(['message' => 'Количество продаж товара за этот год месячно не найдено.']);
    }
    public function productsAmountExpiry(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        $expiresAt = DB::select('SELECT exp.product_id, exp.est_expiry_time FROM ( 
            SELECT orders_products.product_id as product_id, products.productsAmount / AVG(orders_products.productAmount) as est_expiry_time FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            INNER JOIN products ON order_products.product_id = products.id 
            WHERE orders.orderDate BETWEEN ' . $validated['lowerDateTime'] . ' AND ' . $validated['upperDateTime'] . '
            GROUP BY orders_products.product_id
        ) as exp');
        if($expiresAt != null)
            return response()->json(['expiresAt' => $expiresAt], 200);
        else
            return response()->json(['message' => 'За данный отрезок времени не найдено товаров.'], 404);
    }
}