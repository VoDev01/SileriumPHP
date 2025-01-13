<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerAccountingReports extends Controller
{
    public function index()
    {
        return view('seller.accounting_reports.index');
    }
    public function genericReport()
    {
        return view('seller.accounting_reports.generic');
    }
    public function productsReports()
    {
        return view('seller.accounting_reports.products');
    }
    public function taxReport()
    {
        return view('seller.accounting_reports.tax');
    }
}
