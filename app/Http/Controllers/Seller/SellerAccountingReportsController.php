<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Models\Product;
use App\Services\SearchFormPaginateResponseService;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;
use Illuminate\Http\Request;

class SellerAccountingReportsController extends Controller
{
    public function index()
    {
        return view('seller.accounting_reports.index');
    }
    public function genericReport()
    {
        return view('seller.accounting_reports.generic');
    }
    public function productsReports(Request $request)
    {
        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/products/search', 'seller.accounting_reports.products');
        return view('seller.accounting_reports.products', ['inputs' => $inputs, 'queryInputs' => $queryInputs, 'products' => $products]);
    }
    public function productReport(Request $request)
    {
        $product = Product::where('ulid', $request->ulid)->get()->first();
        return view('seller.accounting_reports.product', ['product' => $product]);
    }
    public function taxReport()
    {
        return view('seller.accounting_reports.tax');
    }
    public function searchProducts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('sellerName', $validated))
            $validated['sellerName'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;

        return SearchFormProductsSearchMethod::searchProducts($validated);
    }
}
