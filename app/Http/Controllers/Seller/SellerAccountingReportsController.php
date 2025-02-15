<?php

namespace App\Http\Controllers\Seller;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\Payment\SearchPaymentsRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormPaymentsSearchMethod;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;

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
    public function paymentsReport(Request $request)
    {
        $payments = SearchFormPaginateResponseService::paginate($request, 'payments', 15) ?? Payment::with(['order', 'order.user'])->paginate(15);
        $inputs = [
            new SearchFormInput('name', 'Имя заказчика', 'name', true),
            new SearchFormInput('surname', 'Фамилия заказчика', 'surname', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/payments/search', 'seller.accounting_reports.payments', 'order, order.user');
        return view('seller.accounting_reports.payments', ['payments' => $payments, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
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

        return SearchFormProductsSearchMethod::searchProducts($request, $validated);
    }
    public function searchPayments(SearchPaymentsRequest $request)
    {
        $validated = $request->validated();

        return SearchFormPaymentsSearchMethod::searchPayments($validated);
    }
}