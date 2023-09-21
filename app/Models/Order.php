<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public float $totalPrice;
    public int $orderAmount;
    public string $orderAdress;
    public OrderStatus $orderStatus;
    use HasFactory;
}
