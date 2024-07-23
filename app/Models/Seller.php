<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'ulid',
        'name',
        'organization_name',
        'verified',
        'rating',
        'img',
        'email',
        'email_verified'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'sellers_orders', 'order_id', 'seller_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sellers_products', 'product_id', 'seller_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'sellers_users', 'user_id', 'seller_id');
    }
}
