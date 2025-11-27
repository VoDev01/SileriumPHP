<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Actions\EncodeImageBinaryToBase64Action;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\ReviewsImages
 *
 * @mixin IdeHelperReviewsImages
 * @property int $id
 * @property string $imagePath
 * @property int $review_id
 * @property-read \App\Models\Review $review
 * @method static \Database\Factories\ReviewsImagesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewsImages whereReviewId($value)
 * @mixin \Eloquent
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
