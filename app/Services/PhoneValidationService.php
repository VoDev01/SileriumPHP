<?php
// namespace App\Services;

// use Dadata\DadataClient;

// class PhoneValidationService
// {
//     private $dadata;
//     private $code;

//     function __construct()
//     {
//         $this->dadata = new DadataClient(env('DADATA_TOKEN'), env('DADATA_SECRET'));
//     }

//     public function validate(string $phone, string &$message)
//     {
//         if($phone == null || $phone == "")
//             return;
//         $result = $this->dadata->clean("phone", $phone);
//         if($result['qc'] == 0 || $result['qc'] == 7)
//         {
//             return true;
//         }
//         else if($result['qc'] == 2)
//         {
//             $message = "Мусорное или пустой значение";
//             return false;
//         }
//         else if($result['qc'] == 1)
//         {
//             $message = "Ошибка в распознании телефона";
//             return false;
//         }
//         else if($result['qc'] == 3)
//         {
//             session(['first_phone_warn' => "Было обнаружено несколько телефонов, распознан первый."]);
//             return true;
//         }
//     }
//     public function sendCode(string $phone, bool $alreadyConfirmed)
//     {
//         if($alreadyConfirmed)
//             return;
//     }
//     public function validateCode(int $code)
//     {
//         return $this->code == $code;
//     }
// }