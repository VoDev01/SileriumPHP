<?php
namespace App\Enum;
const en = 0;
const ru = 1;
const color = 2;
enum OrderStatus: string
{
    case ISSUING = "Issuing, Ожидает оформления, #7a7a7a";
    case PENDING = "Pending, Обрабатывается, #fffb00";
    case CACNCELLED = "Cancelled, Отклонен, #e83225";
    case DELIVERY = "Delivery, Отправлен, #0b78a1";
    case REFUND = "Refund, Подлежит возврату, #ff4118";
    case PARTIAL_REFUND = "Partial_refund, Подлежит частичному возврату, #e400ff";
    case RECEIVED = "Received, Получен, #1aa922";
    public static function fromName(string $name){
        
        return constant("self::$name");
    }
}