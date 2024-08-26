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
    
    public $timestamps = false;

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_products', 'order_id', 'product_id');
    }
    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'sellers_users', 'seller_id', 'order_id');
    }

    public static function boot()
    {
        parent::boot();
        self::deleted(function ($model){
            $model->orderStatus = 'CLOSED';
            $model->save();
        });
    }
}
