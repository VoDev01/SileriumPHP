<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIAmountExpirySearchRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsDeleteRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use App\Http\Requests\API\Products\APIProfitSearchRequest;
use App\Http\Requests\API\Products\APIConsumptionSearchRequest;
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
            $sellers = $sellers->where('nickname', 'like', '%' . $validated['sellerName'] . '%')->orWhere('id', $request->sellerId);
        }
        else
        {
            $sellers = Seller::where('nickname', 'like', '%' . $validated['sellerName'] . '%')->orWhere('id', $request->sellerId);
        }

        $sellers = $sellers->get() ?? null;

        if ($sellers !== null)
        {
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
        }
        else
        {
            $products = Product::where('name', 'like', "%{$validated['productName']}%")->get();
        }
        if ($products != null)
            return response()->json(['products' => $products->toArray()], 200);
        else
            return response()->json(['message' => 'Не было найдено товаров с таким именем'], 404);
    }
    public function profitBetweenDate(APIProfitSearchRequest $request)
    {
        $validated = $request->validated();
        $profits = DB::table('products')
            ->selectRaw('products.ulid, products.id, products.name, SUM(op.productsPrice) as profit')
            ->leftJoinSub(
                DB::table('orders_products')->whereIn('order_id', function ($query) use ($validated)
                {
                    $query->select('ulid')->from('orders')
                        ->whereBetween('orderDate', [$validated['lowerDate'], $validated['upperDate']])->get();
                }),
                'op',
                'products.id',
                '=',
                'op.product_id'
            )
            ->where('name', 'like', '%' . $validated['productName'] . '%')
            ->groupBy('products.ulid', 'products.id', 'products.name')
            ->get();
        if ($profits != null)
            return response()->json(['profits' => $profits->toArray()], 200);
        else
            return response()->json(['message' => 'За данный период не найдено доходов.']);
    }
    public function consumptionBetweenDate(APIConsumptionSearchRequest $request)
    {
        $validated = $request->validated();
        $consumption = DB::select('SELECT cons.product_id, cons.prodName, SUM(cons.consumption) as consumption, cons.consumptionDate FROM (
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumption, orders.orderDate as consumptionDate, products.name as prodName FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            INNER JOIN products ON orders_products.product_id = products.id
            WHERE orders.orderDate BETWEEN :lowerDate AND :upperDate
            GROUP BY product_id, consumptionDate, prodName
        ) as cons
        GROUP BY cons.product_id, cons.consumptionDate, cons.prodName
        ORDER BY cons.consumptionDate DESC LIMIT 1', ['lowerDate' => $validated['lowerDate'], 'upperDate' => $validated['upperDate']]);
        if ($consumption != null)
            return response()->json(['consumption' => $consumption], 200);
        else
            return response()->json(['message' => 'Количество продаж товара за указанный период не найдено.'], 404);
    }
    public function amountExpiry(APIAmountExpirySearchRequest $request)
    {
        $validated = $request->validated();
        $expiresAt = DB::select('SELECT exp.product_id, exp.prodName, exp.est_expiry_time FROM ( 
            SELECT orders_products.product_id AS product_id, (products.productAmount / AVG(orders_products.productAmount)) AS est_expiry_time, 
            products.name AS prodName FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            INNER JOIN products ON orders_products.product_id = products.id 
            WHERE orders.orderDate BETWEEN :lowerDate AND :upperDate
            GROUP BY product_id, products.productAmount, prodName
        ) as exp', ['lowerDate' => $validated['lowerDate'], 'upperDate' => $validated['upperDate']]);
        if ($expiresAt != null)
            return response()->json(['expiresAt' => $expiresAt], 200);
        else
            return response()->json(['message' => 'За данный период не найдено товаров.'], 404);
    }
}
