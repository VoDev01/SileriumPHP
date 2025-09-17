<?php

namespace App\Actions;

use Dompdf\Css\Stylesheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class FormatHtmlToPdf
{
    public static function format(string $originalHtml, string $htmlTablePerPage, string $htmlTableRow, array $data, string $cssFilePath, string $insertPagesAfterHtmlElement, int $totalPages = 1, int $itemsAtPage = 30): Response
    {
        $finalPage = array();

        for ($i = 0; $i < $totalPages; $i++)
        {
            $elementPart = array_slice($data, $i * $itemsAtPage, $itemsAtPage);
            $finalPage[$i] = $htmlTablePerPage;
            foreach ($elementPart as $element)
            {
                $element = (object) $element;

                $insertAt = strrpos($finalPage[$i], '<tbody>') + strlen('<tbody>');
                if(preg_match_all('/<tr>\s*?<td>\X*?<\/td>\s*?<\/tr>/mu', $finalPage[$i]))
                    $insertAt = strrpos($finalPage[$i], '</tr>') + strlen('</tr>');

                $finalPage[$i] = substr_replace($finalPage[$i], self::hydrateTableRow($htmlTableRow, $element), $insertAt, 0);
            }
            if(count($data) < $itemsAtPage)
                break;
        }

        $finalPage = substr_replace($originalHtml, implode("\n", $finalPage), strpos($originalHtml, $insertPagesAfterHtmlElement) 
        + strlen($insertPagesAfterHtmlElement), 0);

        $pdf = Pdf::loadHTML($finalPage);
        $pdf->setOptions(['dpi' => 125], true);
        $stylesheet = new Stylesheet($pdf->getDomPDF());
        $stylesheet->load_css_file($cssFilePath);
        $pdf->setCss($stylesheet);
        return $pdf->download();
    }

    private static function hydrateTableRow(string $html, object $data): string
    {       
        foreach($data as $dataVal)
        {
            $html = preg_replace("/<td><\/td>/", "<td>$dataVal</td>", $html, 1);
        }
        return $html;
    }
}