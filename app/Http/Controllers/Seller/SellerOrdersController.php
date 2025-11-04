<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use App\Services\SearchForms\ProductSearchFormService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\SearchForms\SearchFormPaginateResponseService;

class SellerOrdersController extends Controller
{
    public function orders(Request $request)
    {
        try
        {
            $orders = SearchFormPaginateResponseService::paginateRelations($request, 'products', 'orders', 15);

            if (Cache::get('products') == null)
            {
                $productsNames = null;
                $productsAmounts = null;
            }
            else
            {
                if ($orders->total() == 1)
                {
                    $productsNames = substr(Cache::get('products')[0]['name'], 0, 5);
                    $productsAmounts = implode(Cache::get('products')[0]['orders']);
                }
                else
                {
                    $productsNames = new Collection();
                    foreach (Cache::get('products') as $product)
                    {
                        $productsNames->push($product['name']);
                    }
                    $productsNames = substr(implode(', ', $productsNames->toArray()), 0, 5);
                }
            }
            if (!isset($orders))
                throw new NotFoundHttpException('Заказов с данным товаром не найдено');
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }

        $queryInputs = new SearchFormQueryInput("/seller/orders/search", "seller.orders.list", "orders, orders.user");
        $inputs = [
            new SearchFormInput("productName", "Название товара", "productName", true, "productName")
        ];

        return view('seller.orders.list', ['orders' => $orders, 'queryInputs' => $queryInputs, 'inputs' => $inputs, 'productsNames' => $productsNames]);
    }
    public function searchProductsOrders(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        if (!array_key_exists('sellerName', $validated))
            $validated['sellerName'] = Seller::where('id', session('seller_id'))->get()->first()->nickname;

        return (new ProductSearchFormService)->search($validated, redirect: '/seller/orders/list');
    }
}
