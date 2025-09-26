<?php
namespace App\Actions;

/**
 * Converts a specified currency of a product to another using cbr api.
 */
class ConvertCurrencyAction
{
    /**
     * Converts rub to dol.
     * @param float $price
     */
    public static function convertToDol(float $price)
    {
        $rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));
        return $price / $rates->Valute->USD->Value;
    }
}