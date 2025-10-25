<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewRepository
{
    public function index(int $itemsAtPage = 15)
    {
        $reviews = Review::paginate($itemsAtPage); //SearchFormPaginateResponseService::paginate('products',  $request->page, 15) ?? Review::paginate(15);
        return $reviews;
    }

    public function show(string $ulid)
    {
        $review = Review::where('ulid', $ulid)->get()->first();
        return $review;
    }

    /**
     * Create review
     *
     * @param array $validated
     * @param integer $userId
     * @param integer $productId
     * @param array|null $images
     * @return void
     */
    public function create(array $validated, int $userId)
    {
        $validated = array_filter($validated);
        $reviewId = Review::insertGetId(
            array_merge(
                $validated,
                [
                    'ulid' => Str::ulid()->toBase32(),
                    'user_id' => $userId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            )
        );
        if (array_key_exists('review_images', $validated))
        {
            foreach ($validated['review_images'] as $image)
            {
                $reviewName = 'rev_' . Str::random(32);
                $path = '/images/reviews/' . $reviewName;
                while(Storage::has($path))
                {
                    $reviewName = 'rev_' . Str::random(32);
                    $path = '/images/reviews/' . $reviewName;
                }
                $image = Storage::putFile($path, $image);
                DB::insert('INSERT INTO reviews_images (imagePath, review_id) VALUES (?, ?)', [$path, $reviewId]);
            }
        }
        $review = Review::find($reviewId);
        return $review;
    }
    /**
     * Update review
     *
     * @param array $validated
     * @return void
     */ 
    public function update(array $validated)
    {
        $validated['ulid'] = $validated['id'];
        unset($validated['id']);
        $validated = array_filter($validated);
        Review::where('ulid', $validated['ulid'])->update(
            array_merge(
                array_filter($validated, fn($elem) => $elem !== 'ulid' && $elem !== null),
                [
                    'updated_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s')
                ]
            )
        );
    }

    public function updateImages(int $reviewId, array $images)
    {
        foreach ($images as $image)
        {
            DB::update('UPDATE reviews_images SET imagePath = ? WHERE review_id = ?', [$image, $reviewId]);
        }
    }

    public function delete(string $id)
    {
        $review = Review::where('ulid', $id)->get()->first();

        if ($review === null)
            return false;

        $review->delete();

        return true;
    }
}
