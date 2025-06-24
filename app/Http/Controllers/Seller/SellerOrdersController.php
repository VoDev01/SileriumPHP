<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Models\Order;
use App\Models\Seller;
use App\Services\SearchFormPaginateResponseService;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormHiddenInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SellerOrdersController extends Controller
{
    public function orders(Request $request)
    {
        $orders = SearchFormPaginateResponseService::paginateRelations($request, 'products', 'orders', 15);
        if (session('products') == null)
        {
            $productsNames = null;
            $productsAmounts = null;
        }
        else
        {
            if ($orders->total() == 1)
            {
                $productsNames = substr(session('products')[0]['name'], 0, 5);
                dd(session('products'));
                $productsAmounts = implode(session('products')[0]['orders']);
            }
            else
            {
                $productsNames = new Collection();
                foreach (session('products') as $product)
                {
                    $productsNames->push($product['name']);
                }
                $productsNames = substr(implode(', ', $productsNames->toArray()), 0, 5);
            }
        }
        $queryInputs = new SearchFormQueryInput("/seller/orders/searchOrders", "seller.orders.list", "orders, orders.user");
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

        return SearchFormProductsSearchMethod::searchProducts($request, $validated);
    }
}
