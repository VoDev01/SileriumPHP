<?php
namespace App\Enum;
const en = 0;
const ru = 1;
const color = 2;
enum OrderStatus: string
{
    case ISSUING = "Issuing, Ожидает оформления, #7a7a7a";
    case PENDING = "Pending, Обрабатывается, #a0a147";
    case CLOSED = "Closed, Отозван, #e83225";
    case DELIVERY = "Delivery, Отправлен, #0b78a1";
    case RECEIVED = "Received, Получен, #1aa922";
    public static function fromName(string $name){
        
        return constant("self::$name");
    }
}