<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'available' => 'boolean'
    ];
    protected $fillable = [
        'name',
        'description',
        'priceRub',
        'stockAmount',
        'available',
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
        return $this->hasMany(Order::class);
    }
    public function productSpecifications()
    {
        return $this->belongsToMany(ProductSpecifications::class, 'products_specifications', 'specification_id', "product_id");
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    protected static function newFactory()
    {
        return ProductFactory::new();
    }
    
}