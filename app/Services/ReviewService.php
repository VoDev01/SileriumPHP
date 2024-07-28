<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ReviewService 
{
    public function make(array $validatedInput, int $userId, int $productId, array $images = null)
    {
        $reviewId = Review::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'title' => $validatedInput['title'],
            'pros' => $validatedInput['pros'],
            'cons' => $validatedInput['cons'],
            'comment' => $validatedInput['comment'],
            'rating' => $validatedInput['rating'],
            'user_id' => $userId,
            'product_id' => $productId,
            'createdAt' => Carbon::now(),
            'updatedAt' => Carbon::now()
        ]);
        if($images != null)
        {
            for ($i=0; $i < $images->count(); $i++) { 
                DB::insert('INSERT INTO reviews_images (imagePath, review_id) VALUES (?, ?)', [$images[$i], $reviewId]);
            }
        }
        $review = Review::find($reviewId);
        return $review;
    }
    public function update(int $id, array $validatedInput, int $productId, int $userId)
    {
        Review::find($id)->update([
            'title' => $validatedInput['title'],
            'pros' => $validatedInput['pros'],
            'cons' => $validatedInput['cons'],
            'comment' => $validatedInput['comment'],
            'review_images' => $validatedInput['review_images'],
            'product_id' => $productId,
            'user_id' => $userId
        ]);
    }
}