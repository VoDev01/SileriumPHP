<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSubcategory
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
