<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperSeller
 */
class Seller extends Model
{
    use HasFactory, HasUlids;

    //protected $primaryKey = 'ulid';

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

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function orders()
    {
        return $this->HasMany(Order::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
