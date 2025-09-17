<?php

namespace App\Http\Controllers\Formatting;

use App\Actions\FormatHtmlToPdf;
use App\Http\Controllers\Controller;
use App\Http\Requests\Formatting\PdfFormattingRequest;

class PdfFormatterController extends Controller
{
    public function formatPDF(PdfFormattingRequest $request)
    {
        $validated = $request->validated();
        return FormatHtmlToPdf::format(
            $validated["pageHtml"],
            $validated["tableHtml"],
            $validated["tableRowHtml"],
            $validated["data"],
            public_path('css/pdf-style.css'),
            $validated["insertAfterElement"],
            $validated["totalPages"] ?? 1
        );
    }
}
