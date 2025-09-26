<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewService 
{
    /**
     * Make review
     *
     * @param array $validatedInput
     * @param integer $userId
     * @param integer $productId
     * @param array|null $images
     * @return void
     */
    public static function make(array $validatedInput, int $userId, int $productId, array $images = null)
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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
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
    /**
     * Update review
     *
     * @param integer $id
     * @param array $validatedInput
     * @param integer $productId
     * @param integer $userId
     * @return void
     */
    public static function update(int $id, array $validatedInput, int $productId, int $userId)
    {
        Review::where('id', $id)->update([
            'title' => $validatedInput['title'],
            'pros' => $validatedInput['pros'],
            'cons' => $validatedInput['cons'],
            'comment' => $validatedInput['comment'],
            'updated_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'rating' => $validatedInput['rating'],
            'product_id' => $productId,
            'user_id' => $userId
        ]);
    }
}