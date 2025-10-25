<?php

namespace App\Services\Products;

use App\Enum\SortOrder;
use App\Models\Product;
use App\Actions\OrderItemsAction;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function profitBetweenDate(array $validated)
    {
        $profits = DB::select(
            'SELECT p.ulid, p.id, p.name, SUM(op.productsPrice) as profit FROM products as p 
            INNER JOIN orders_products as op ON p.id = op.product_id
            INNER JOIN orders as o ON op.order_id = o.ulid
            INNER JOIN sellers as s ON p.seller_id = s.id
            WHERE (o.created_at BETWEEN ? AND ?) AND (p.name LIKE ? OR s.nickname LIKE ?)
            GROUP BY p.ulid, p.id, p.name',
            [
                $validated['lowerDate'],
                $validated['upperDate'],
                '%' . $validated['productName'] . '%',
                '%' . $validated['sellerName'] . '%'
            ]
        );
        return $profits;
    }

    public function consumptionBetweenDate(array $validated)
    {
        $consumption = DB::select(
            'SELECT cons.product_id, cons.prodName, SUM(cons.consumption) as consumption, cons.consumptionDate FROM (
            SELECT orders_products.product_id as product_id, SUM(orders_products.productAmount) as consumption, orders.created_at as consumptionDate, products.name as prodName FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            INNER JOIN products ON orders_products.product_id = products.id
            INNER JOIN sellers ON products.seller_id = sellers.id
            WHERE (orders.created_at BETWEEN ? AND ?) AND (products.name LIKE ? OR sellers.nickname LIKE ?)
            GROUP BY product_id, consumptionDate, prodName
        ) as cons
        GROUP BY cons.product_id, cons.consumptionDate, cons.prodName
        ORDER BY cons.consumptionDate DESC LIMIT 1',
            [
                $validated['lowerDate'],
                $validated['upperDate'],
                '%' . $validated['productName'] . '%',
                '%' . $validated['sellerName'] . '%'
            ]
        );

        return $consumption;
    }

    public function amountExpiry(array $validated)
    {
        $expiresAt = DB::select('SELECT exp.product_id, exp.prodName, exp.est_expiry_time FROM ( 
            SELECT orders_products.product_id AS product_id, (products.productAmount / AVG(orders_products.productAmount)) AS est_expiry_time, 
            products.name AS prodName FROM orders_products
            INNER JOIN orders ON orders_products.order_id = orders.ulid
            INNER JOIN products ON orders_products.product_id = products.id 
            WHERE orders.created_at BETWEEN ? AND ?
            GROUP BY product_id, products.productAmount, prodName
        ) as exp', [
            $validated['lowerDate'],
            $validated['upperDate']
        ]);

        return $expiresAt;
    }

    /**
     * Filter products by some column or name
     *
     * @param string $select
     * @param string $subcategory
     * @param string $name
     * @param integer $available
     * @param integer $sortOrder
     * @param integer $items
     * @return mixed
     */
    public function getFilteredProducts(
        string $subcategory = "all",
        string $name = "",
        array|string $select = "ulid, id, name",
        int $available = 1,
        int $sortOrder = SortOrder::NAME_DESC,
        int $items = 15
    )
    {
        if ($subcategory === "all" && $name === "")
        {
            $products = Product::with('images')
                ->where('available', $available)
                ->orderByRaw(OrderItemsAction::orderItem($sortOrder))
                ->paginate($items, $select);
        }
        else if ($subcategory === "all")
        {
            $products = Product::with('images')
                ->where('name', 'like', '%' . $name . '%')
                ->where('available', $available)
                ->orderByRaw(OrderItemsAction::orderItem($sortOrder))
                ->paginate($items, $select);
        }
        else
        {
            $products = Product::with('images')
                ->where('name', 'like', '%' . $name . '%')
                ->where('subcategory_id', $subcategory)
                ->where('available', $available)
                ->orderByRaw(OrderItemsAction::orderItem($sortOrder))
                ->paginate($items, $select);
        }
        return $products;
    }

}
