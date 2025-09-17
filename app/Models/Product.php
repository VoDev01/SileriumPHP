<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Product
 *
 * @mixin IdeHelperProduct
 * @property string $ulid
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $priceRub
 * @property bool $available
 * @property int $subcategory_id
 * @property int $timesPurchased
 * @property int $productAmount
 * @property int $seller_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductSpecifications> $productSpecifications
 * @property-read int|null $product_specifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\Seller $seller
 * @property-read \App\Models\Subcategory|null $subcategory
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceRub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSubcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTimesPurchased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUlid($value)
 * @mixin \Eloquent
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