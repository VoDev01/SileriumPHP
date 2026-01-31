<?php

namespace App\Services\Formatting;

use Dompdf\Css\Stylesheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

/**
 * Fromats html to pdf
 */
class FormatHtmlToPdf
{
    /**
     * Formats only table of the original html page and paginates its contents across all pdf document pages
     *
     * @param string $originalHtml Unmodified html page that will contain constructed table
     * @param string $htmlTablePerPage Html code of the table that should only have headers and tbody
     * @param string $htmlTableRow Html code of the table row that will be injected in the $htmlTablePerPage 
     * and that has as many td elements as there is properties in the $data
     * @param array $data Data to inject in td elements
     * @param string $cssFilePath Path to the css file for pdf document styling
     * @param string $insertPagesAfterHtmlElement Html tag in which all tables will be inserted
     * @param integer $totalPages
     * @param integer $itemsAtPage
     * @return Response
     */
    public static function formatTable(string $originalHtml, string $htmlTablePerPage, string $htmlTableRow, array $data, string $cssFilePath, string $insertPagesAfterHtmlElement, int $totalPages = 1, int $itemsAtPage = 30): Response
    {
        $finalPage = array();

        for ($i = 0; $i < $totalPages; $i++)
        {
            //Slice of the data at current page
            $elementPart = array_slice($data, $i * $itemsAtPage, $itemsAtPage);
            //Setup initial html
            $finalPage[$i] = $htmlTablePerPage;

            //Insert tr after tbody if there isn't any, or after the last tr
            $insertAt = strrpos($finalPage[$i], '<tbody>') + strlen('<tbody>');
            if (preg_match_all('/<tr>\s*?<td>\X*?<\/td>\s*?<\/tr>/mu', $finalPage[$i]))
                $insertAt = strrpos($finalPage[$i], '</tr>') + strlen('</tr>');

            //Hydrate td's with data
            $finalPage[$i] = substr_replace($finalPage[$i], self::hydrateTableRow($htmlTableRow, $elementPart), $insertAt, 0);

            if (count($data) < $itemsAtPage)
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

    /**
     * Injects data in td elements of the table rows
     *
     * @param string $html Row html with empty td elements
     * @param array $data Data to hydrate with
     * @return string
     */
    private static function hydrateTableRow(string $html, array $data): string
    {
        foreach ($data as $dataVal)
        {
            $html = preg_replace("/<td><\/td>/", "<td>$dataVal</td>", $html, 1);
        }
        return $html;
    }
}
