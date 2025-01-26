<?php

namespace Tests\Unit\Actions;

use App\Actions\ConvertCurrencyAction;
use PHPUnit\Framework\TestCase;

class ConvertCurrencyActionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testConvertCurrency()
    {
        $oneDollarRub = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'))->Valute->USD->Value;
        $dollar = ConvertCurrencyAction::convertToDol($oneDollarRub);
        $this->assertTrue($dollar == 1.0);
    }
}
