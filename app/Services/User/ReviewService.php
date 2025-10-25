<?php

namespace App\Services\User;

use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function averageRating(array $validated)
    {
        $reviews = DB::table('reviews')
            ->selectRaw('reviews.product_id, AVG(reviews.rating) as averageRating')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->whereRaw('products.name like ?', [
                '%' . $validated['productName'] . '%'
            ])
            ->groupBy('reviews.product_id')
            ->get();
        return $reviews;
    }

    public function ratingCount(array $validated)
    {
        $ratingCount = DB::select('SELECT reviews.product_id,
        SUM(CASE WHEN reviews.rating = 5 THEN 1 else 0 end) as rating5,
        SUM(CASE WHEN reviews.rating = 4 THEN 1 else 0 end) as rating4,
        SUM(CASE WHEN reviews.rating = 3 THEN 1 else 0 end) as rating3,
        SUM(CASE WHEN reviews.rating = 2 THEN 1 else 0 end) as rating2,
        SUM(CASE WHEN reviews.rating = 1 THEN 1 else 0 end) as rating1
        FROM reviews
        INNER JOIN products ON reviews.product_id = products.id
        WHERE products.name LIKE ?
        GROUP BY reviews.product_id', ['%' . $validated['productName'] . '%']);

        return $ratingCount;
    }
}