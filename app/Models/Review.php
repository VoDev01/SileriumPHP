<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    protected $fillable = [
        'title',
        'pros',
        'cons',
        'comment',
        'rating',
        'createdAt',
        'updatedAt',
        'user_id',
        'product_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(ReviewsImages::class);
    }
}
