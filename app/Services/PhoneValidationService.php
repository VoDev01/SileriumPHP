<?php
namespace App\Services;

use Dadata\DadataClient;

class PhoneValidationService
{
    private $dadata = new DadataClient(env('DADATA_TOKEN '), env('DADATA_SECRET '));
    private $code;
    public function validate(string $phone, string &$message)
    {
        $result = $this->dadata->clean("phone", $phone);
        if($result['qc'] == 0 || $result['qc'] == 7)
        {
            return true;
        }
        else if($result['qc'] == 2)
        {
            $message = "Мусорное или пустой значение";
            return false;
        }
        else if($result['qc'] == 1)
        {
            $message = "Ошибка в распознании телефона";
            return false;
        }
        else if($result['qc'] == 3)
        {
            session(['first_phone_warn' => "Было обнаружено несколько телефонов, распознан первый."]);
            return true;
        }
    }
    public function sendCode(string $phone, bool $alreadyConfirmed)
    {
        if($alreadyConfirmed)
            return;
    }
    public function validateCode(int $code)
    {
        return $this->code == $code;
    }
}