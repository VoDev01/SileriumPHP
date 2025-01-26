<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, HasUlids;

    //protected $primaryKey = 'ulid';

    public $timestamps = false;

    protected $casts = [
        'available' => 'boolean'
    ];
    protected $fillable = [
        'name',
        'description',
        'priceRub',
        'available',
        'productAmount',
        'subcategory_id',
        'seller_id'
    ];
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function subcategory(): HasOne
    {
        return $this->hasOne(Subcategory::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_products', 'product_id', 'order_id');
    }
    public function productSpecifications()
    {
        return $this->belongsToMany(ProductSpecifications::class, 'products_specifications', "product_id", 'specification_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    protected static function newFactory()
    {
        return ProductFactory::new();
    }
    
}