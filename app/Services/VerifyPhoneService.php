<?php
namespace App\Services;

use stdClass;
use Illuminate\Support\Facades\Log;

class VerifyPhoneService
{
    public static int $verification_code;
    public static function send(string $phone = '79385157102')
    {
        $smsru = new SMSRU(env('SMSRU_API_ID'));
        $verification_code = rand(1111, 9999);

        $data = new stdClass();
        $data->to = $phone;
        $data->text = "Ваш код подтверждения: " . $verification_code;

        $response = $smsru->send_one($data);

        if($response->status == "OK")
        {
            Log::info("Message sent succesfully");
            Log::info("Message ID: " . $response->sms_id);
            Log::info("New balance: " . $response->balance);
            return 100;
        }
        else
        {
            Log::error("Message failed to be sent");
            Log::error("Error code: " . $response->status_code);
            Log::error("Error message: " . $response->status_text);
            return $response->status_code;
        }
    }
}