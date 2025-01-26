<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperReviewsImages
 */
class ReviewsImages extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
