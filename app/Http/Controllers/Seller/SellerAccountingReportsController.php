<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Payment\SearchPaymentsRequest;
use App\Services\SearchForms\PaymentSearchFormService;
use App\Services\SearchForms\ProductSearchFormService;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Services\SearchForms\SearchFormPaginateResponseService;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use App\Services\SearchForms\FormInputData\SearchFormHiddenInput;

class SellerAccountingReportsController extends Controller
{
    public function genericReport()
    {
        return view('seller.accounting_reports.generic');
    }
    public function paymentsReport(Request $request)
    {
        $payments = SearchFormPaginateResponseService::paginate('payments', $request->page ?? 1, 15) ?? Payment::with(['order', 'order.user'])->paginate(15); 
        $inputs = [
            new SearchFormInput('name', 'Имя заказчика', 'name', true),
            new SearchFormInput('surname', 'Фамилия заказчика', 'surname', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/payments/search', 'seller.accounting_reports.payments', 'order, order.user');
        return view('seller.accounting_reports.payments', ['payments' => $payments, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function productsReports(Request $request)
    {
        $products = SearchFormPaginateResponseService::paginate('products', $request->page ?? 1, 15); 

        $currnetPage = isset($products) ? $products->currentPage() : null;
        $totalPages = isset($products) ? $products->lastPage() : null;

        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $hiddenInputs = [
            new SearchFormHiddenInput('sellerId', 'sellerId', Seller::where('user_id', Auth::id())->get(['id'])->first()->id)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/products/search', 'seller.products');

        return view('seller.accounting_reports.products', [
            'inputs' => $inputs,
            'queryInputs' => $queryInputs,
            'products' => $products,
            'hiddenInputs' => $hiddenInputs,
            'page' => $currnetPage,
            'totalPages' => $totalPages,
            'searchName' => $request->session()->get('searchName') ?? null
        ]);
    }
    public function productReport(Request $request, Product $product)
    {
        if ($request->session()->get('data') !== null)
        {
            return view('seller.accounting_reports.product', ['data' => $request->session()->get('data'), 'product' => $product]);
        }
        return view('seller.accounting_reports.product', ['product' => $product]);
    }
    public function formProductReport(Request $request)
    {
        $product = Product::where('ulid', $request->ulid)->get(['id', 'ulid', 'name', 'productAmount'])->first();

        $lowerDate = (new Carbon)->createFromFormat('d-m', $request->lowerDate)->setYear($request->year)->format('d-m-Y');
        $upperDate = (new Carbon)->createFromFormat('d-m', $request->upperDate)->setYear($request->year)->format('d-m-Y');

        $sellAmount = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/consumption_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('consumption');
        $sellAmount = $sellAmount[0]['consumption'] ?? null;

        $income = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/profit_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('profits');
        $income = $income[0]['profit'] ?? null;

        $expiry = Http::acceptJson()
            ->withoutVerifying()
            ->post(env('APP_URL') . '/api/v1/products/profit_between_date', [
                'productName' => $product->name,
                'lowerDate' => $lowerDate,
                'upperDate' => $upperDate
            ])
            ->json('expiresAt');
        $expiry = $expiry[0]['est_expires_at'] ?? null;

        $data = (object) [
            'sellAmount' => $sellAmount,
            'income' => $income,
            'expiry' => $expiry,
            'upperDate' => $request->upperDate,
            'lowerDate' => $request->lowerDate,
            'year' => $request->year
        ];

        return redirect()->route('seller.accounting_reports.product', ['product' => $product])->with('data', $data);
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

        return (new ProductSearchFormService)->search($validated, redirect: "/seller/accounting_reports/products");
    }
    public function searchPayments(SearchPaymentsRequest $request)
    {
        $validated = $request->validated();

        return (new PaymentSearchFormService)->search($validated);
    }
}
