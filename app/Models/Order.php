<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $fillable = [
        'ulid',
        'user_id',
        'product_id',
        'totalPrice',
        'orderAmount',
        'orderAdress',
        'orderStatus'
    ];
    
    protected $casts = [
        'deleted_at' => 'datetime', 
        'updated_at' => 'datetime'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_products', 'order_id', 'product_id');
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public static function boot()
    {
        parent::boot();
        static::softDeleted(function($order) {
            $order->orderStatus = 'CLOSED';
            $order->save();
        });
    }
}
