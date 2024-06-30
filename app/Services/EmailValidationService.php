<?php

namespace App\Services;

use Dadata\DadataClient;

class EmailValidationService
{
    private DadataClient $dadata;

    function __construct()
    {
        $this->dadata = new DadataClient(env('DADATA_TOKEN'), env('DADATA_SECRET'));
    }

    public function validate(string $email, string &$message)
    {
        $response = $this->dadata->clean("email", $email);
        if ($response['qc'] == 2 )
        {
            $message = "Мусорное или пустое значение";
            return false;
        }
        else if($response['qc'] == 3)
        {
            $message = "Одноразовый адрес";
            return false;
        }
        else if($response['qc'] == 1)
        {
            $message = "Не соответствует общепринятым правилам";
            return false;
        }
        else if($response['qc'] == 4)
        {
            $email = $response['email'];
            return true;
        }
        else if($response['qc'] == 0)
        {
            return true;
        }
    }
}
