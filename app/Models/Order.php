<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'user_id',
        'product_id',
        'totalPrice',
        'orderAmount',
        'orderAdress',
        'orderStatus'
    ];
    public $timestamps = false;
    public function users()
    {
        return $this->belongsToMany(User::class, 'orders_users', 'user_id', 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
