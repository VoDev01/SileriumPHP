<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subcategory
 *
 * @mixin IdeHelperSubcategory
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property int $category_id
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Product|null $products
 * @method static \Database\Factories\SubcategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereName($value)
 * @mixin \Eloquent
 */
class Subcategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'image',
        'category_id'
    ];

    public $timestamps = false;

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
