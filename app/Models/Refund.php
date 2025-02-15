<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Refund
 *
 * @property int $id
 * @property string $payment_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Refund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund query()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereUserId($value)
 * @mixin \Eloquent
 */
class Refund extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'payment_id',
        'order_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
