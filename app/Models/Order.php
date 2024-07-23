<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
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
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'sellers_users', 'seller_id', 'order_id');
    }
}
