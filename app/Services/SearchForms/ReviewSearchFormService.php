<?php

namespace App\Services\SearchForms;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Services\SearchForms\Base\SearchFormBase;

class ReviewSearchFormService extends SearchFormBase
{
    public function searchUserReviews(array $validated)
    {
        $reviews = DB::select(
            'SELECT reviews.*, users.ulid as user_ulid FROM reviews 
        INNER JOIN users ON reviews.user_id = users.id 
        WHERE users.ulid = ? OR users.email LIKE ?',
            [
                $validated['userId'],
                '%' . $validated['userEmail'] . '%'
            ]
        );

        return $reviews;
    }

    public function searchSellerReviews(array $validated)
    {
        $reviews = DB::select(
            'SELECT reviews.*, sellers.ulid as seller_ulid FROM reviews 
        INNER JOIN products ON reviews.product_id = products.id
        INNER JOIN sellers ON products.seller_id = sellers.id
        WHERE sellers.ulid = ? OR sellers.organization_email LIKE ?',
            [
                $validated['sellerId'],
                '%' . $validated['sellerEmail'] . '%'
            ]
        );
        return $reviews;
    }

    public function searchProductReviews(array $validated)
    {
        $reviews = DB::select(
            "SELECT 
            r.id,
            r.ulid,
            r.title,
            r.pros,
            r.cons,
            r.comment,
            r.rating,
            r.created_at,
            r.updated_at,
            r.product_id,
            u.ulid as user_ulid,
            u.name,
            u.surname,
            u.profilePicture
            FROM reviews as r
            INNER JOIN products ON r.product_id = products.id
            INNER JOIN sellers ON products.seller_id = sellers.id
            INNER JOIN users as u ON r.user_id = u.id
            WHERE (products.name LIKE ? OR products.id = ?) AND sellers.nickname LIKE ?",
            [

                "%{$validated['productName']}%",
                $validated['productId'] ?? null,
                "%{$validated['sellerName']}"
            ]
        );


        foreach ($reviews as $review)
        {
            $review->name = Crypt::decryptString($review->name);
            $review->surname = Crypt::decryptString($review->surname);
        }

        return $reviews;
    }
}
