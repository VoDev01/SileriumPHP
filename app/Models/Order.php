<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Order
 *
 * @mixin IdeHelperOrder
 * @property string $ulid
 * @property float $totalPrice
 * @property string $orderDate
 * @property string $orderAdress
 * @property string $orderStatus
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderAdress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $fillable = [
        'ulid',
        'user_id',
        'orderDate',
        'orderAdress',
        'orderStatus',
        'deleted_at',
        'updated_at',
        'totalPrice'
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
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function refunds()
    {
        return $this->hasMany(Refund::class);
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
