<?php

namespace App\Models;

use Database\Factories\ProductSpecificationsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductSpecifications
 *
 * @mixin IdeHelperProductSpecifications
 * @property int $id
 * @property string $name
 * @property string $specification
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\ProductSpecificationsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSpecifications whereSpecification($value)
 * @mixin \Eloquent
 */
class ProductSpecifications extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_specifications', "product_id", "specification_id");
    }
    protected static function newFactory()
    {
        return ProductSpecificationsFactory::new();
    }
}
