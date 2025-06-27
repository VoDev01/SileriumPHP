<?php
namespace App\Actions;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MakeReviewAction 
{
    public static function make(array $validated_input, int $user_id, int $product_id, array $images = null)
    {
        $reviewId = Review::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'title' => $validated_input['title'],
            'pros' => $validated_input['pros'],
            'cons' => $validated_input['cons'],
            'comment' => $validated_input['comment'],
            'rating' => $validated_input['rating'],
            'user_id' => $user_id,
            'product_id' => $product_id,
            'createdAt' => Carbon::now(),
            'updatedAt' => Carbon::now()
        ]);
        if($images != null)
        {
            for ($i=0; $i < count($images); $i++) { 
                DB::insert('INSERT INTO reviews_images (imagePath, review_id) VALUES (?, ?)', [$images[$i], $reviewId]);
            }
        }
        $review = Review::find($reviewId);
        return $review;
    }
}