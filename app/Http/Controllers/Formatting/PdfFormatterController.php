<?php

namespace App\Http\Controllers\Formatting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Formatting\FormatHtmlToPdf;
use App\Http\Requests\Formatting\PdfFormattingRequest;

class PdfFormatterController extends Controller
{
    public function formatPDF(Request $request)
    {
        $cacheKey = $request->cacheKey;

        $data = Cache::get($cacheKey);

        if (!$data)
        {
            abort(404, 'Срок действия данных истек. Попробуйте еще раз.');
        }

        return FormatHtmlToPdf::formatTable(
            $data["pageHtml"],
            $data["tableHtml"],
            $data["tableRowHtml"],
            $data["data"],
            public_path('css/pdf-style.css'),
            $data["insertAfterElement"],
            $data["totalPages"] ?? 1
        );
    }

    public function cachePDFData(Request $request)
    {
        $cacheKey = 'pdf_' . auth()->id() . '_' . uniqid();

        Cache::put($cacheKey, $request->all(), now()->addMinutes(5));

        return response()->json(['cacheKey' => $cacheKey]);
    }
}
