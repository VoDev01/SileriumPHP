<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Services\SearchFormPaginateResponseService;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;

class SellerAccountingReportsFormatterController extends Controller
{
    public function formatPDF(Request $request)
    {

        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/accounting_reports/products/search', 'seller.accounting_reports.products');
        //$pdf = Pdf::loadHTML($request->html);
        $pdf = Pdf::loadView('seller.accounting_reports.products', ['inputs' => $inputs, 'queryInputs' => $queryInputs, 'products' => $products], [], 'utf-8');
        return $pdf->download();
    }
}
