<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class SellerAccountingReportsFormatterController extends Controller
{
    public function formatPDF(Request $request)
    {
        $pdf = Pdf::loadHtml($request->pageHtml);
        $pdf->setOption('dpi', 150);
        return $pdf->download();
    }
}
