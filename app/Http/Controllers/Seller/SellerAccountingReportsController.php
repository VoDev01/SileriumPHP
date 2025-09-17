<?php

namespace App\Http\Controllers\Seller;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\Payment\SearchPaymentsRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Models\Seller;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormHiddenInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormPaymentsSearchMethod;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SellerAccountingReportsController extends Controller
{
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
        $currnetPage = isset($products) ? $products->currentPage() : null;
        $totalPages = isset($products) ? $products->lastPage() : null;
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $hiddenInputs = [
            new SearchFormHiddenInput('sellerId', 'sellerId', Seller::where('user_id', Auth::id())->get(['id'])->first()->id)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/products/search', 'seller.accounting_reports.products');
        return view('seller.accounting_reports.products', ['inputs' => $inputs, 'queryInputs' => $queryInputs, 'products' => $products, 'hiddenInputs' => $hiddenInputs, 'page' => $currnetPage, 'totalPages' => $totalPages, 'searchName' => $request->session()->get('searchName') ?? null]);
    }
    public function productReport(Request $request, Product $product)
    {
        if(isset($request->data))
            return view('seller.accounting_reports.product', [ 'data' => $request->data ]); 
        return view('seller.accounting_reports.product', [ 'product' => $product ]);
    }
    public function formProductReport(Request $request)
    {
        $product = Product::where('ulid', $request->ulid)->get(['id', 'ulid', 'name', 'productAmount'])->first();

        $lowerDate = (new Carbon($request->lowerDate))->setYear($request->year)->format('d-m-Y H:i:s');
        $upperDate = (new Carbon($request->upperDate))->setYear($request->year)->format('d-m-Y H:i:s');

        $sellAmount = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/consumption_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('consumption');
        $income = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/profit_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('profits');
        $expiry = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/profit_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('expiresAt');

        $data = (object) [
            'product' => $product,
            'sellAmount' => $sellAmount,
            'income' => $income,
            'expiry' => $expiry,
            'upperDate' => $request->upperDate,
            'lowerDate' => $request->lowerDate,
            'year' => $request->year
        ];

        return redirect()->route('seller.accounting_reports.product', ['data' => $data]);
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

        return SearchFormProductsSearchMethod::search($request, $validated);
    }
    public function searchPayments(SearchPaymentsRequest $request)
    {
        $validated = $request->validated();

        return SearchFormPaymentsSearchMethod::search($request, $validated);
    }
}
