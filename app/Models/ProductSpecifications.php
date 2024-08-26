<?php

namespace App\Models;

use Database\Factories\ProductSpecificationsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProductSpecifications
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
