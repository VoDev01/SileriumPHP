<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function subcategory(): HasOne
    {
        return $this->hasOne(Subcategory::class);
    }
}