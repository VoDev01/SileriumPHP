<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Actions\EncodeImageBinaryToBase64Action;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\ProductImage
 *
 * @mixin IdeHelperProductImage
 * @property int $id
 * @property string $imagePath
 * @property int $product_id
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\ProductImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductId($value)
 * @mixin \Eloquent
 */
class ProductImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // protected function imagePath() : Attribute
    // {
    //     return Attribute::get(function($value){
    //         return Storage::url($value);
    //     });
    // }
}
