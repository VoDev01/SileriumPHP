<?php

namespace App\Http\Controllers\API\V1;

use Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\API\Reviews\APIDeleteReviewRequest;
use App\Http\Requests\API\Reviews\APIUpdateReviewRequest;
use App\Http\Requests\API\Reviews\APIUserReviewsSearchRequest;
use App\Http\Requests\API\Reviews\APIProductReviewsSearchRequest;
use App\Http\Requests\API\Reviews\APIProductsAverageRatingRequest;
use App\Http\Requests\API\Reviews\APIProductsRatingCountRequest;
use Illuminate\Support\Facades\Crypt;

class APIReviewsController extends Controller
{

    public function index(Request $request)
    {
        $reviews = Review::paginate(15); //SearchFormPaginateResponseService::paginate($request, 'products', 15) ?? Review::paginate(15);
        return response()->json(['reviews' => $reviews]);
    }

    public function update(APIUpdateReviewRequest $request)
    {
        $validated = $request->validated();
        $review = Review::where('ulid', $validated['id'])->get()->first();
        Review::where('ulid', $validated['id'])->update([
            'title' => $validated['title'] ?? $review->title,
            'pros' => $validated['pros'] ?? $review->pros,
            'cons' => $validated['cons'] ?? $review->cons,
            'comment' => $validated['comment'] ?? $review->comment,
            'rating' => $validated['rating'] ?? $review->rating,
            'updated_at' => Carbon::now()
        ]);
        return response()->json($review);
    }

    public function delete(APIDeleteReviewRequest $request)
    {
        $validated = $request->validated();
        $review = Review::where('ulid', $validated['id']);
        $review->delete();
        return response()->json();
    }

    public function searchUserReviews(APIUserReviewsSearchRequest $request)
    {
        $validated = $request->validated();
        //$reviews = User::with('reviews')->where('ulid', $validated['userId'])->orWhere('email', $validated['userEmail'])->get()->first()->reviews;
        $reviews = DB::select('SELECT reviews.* FROM reviews INNER JOIN users ON reviews.user_id = users.id WHERE users.ulid = ? OR email = ?', [
            $validated['userId'],
            $validated['userEmail']
        ]);
        if ($reviews != null)
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов запрашиваемого пользователя не найдено.'], 404);
    }
    public function searchProductReviews(APIProductReviewsSearchRequest $request)
    {
        $validated = $request->validated();
        //$sellers = Seller::where('nickname', 'like', '%' . $validated['sellerName'] . '%')->get();
        //$products = Product::with(['reviews', 'seller'])->where('name', 'like', '%'.$validated['productName'].'%')->get();
        $sellers = DB::select('SELECT ulid FROM sellers WHERE nickname LIKE ?', ["%{$validated['sellerName']}%"]);

        $sellers = array_map(function ($element)
        {
            return $element->ulid;
        }, $sellers);

        $placeholders = implode(', ', array_fill(0, count($sellers), '?'));

        $params = [
            "%{$validated['productName']}%",
            $validated['productId'] ?? null,
        ];
        foreach ($sellers as $ulid)
        {
            $params[] = $ulid;
        }

        $query = "SELECT 
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
            u.id as user_id, 
            u.name,
            u.surname,
            u.profilePicture
            FROM reviews as r
            INNER JOIN products ON r.product_id = products.id
            INNER JOIN sellers ON products.seller_id = sellers.id
            INNER JOIN users as u ON r.user_id = u.id
            WHERE (products.name LIKE ? OR products.id = ?) AND sellers.ulid IN ($placeholders)";

        $reviews = DB::select(
            $query,
            $params
        );

        foreach ($reviews as $review)
        {
            $review->name = Crypt::decryptString($review->name);
            $review->surname = Crypt::decryptString($review->surname);
        }

        if (!empty($reviews))
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов на запрашиваемый товар не найдено.'], 404);
    }

    public function averageRating(APIProductsAverageRatingRequest $request)
    {
        $validated = $request->validated();
        $avgRating = DB::table('reviews')
            ->selectRaw('reviews.product_id, AVG(reviews.rating) as averageRating')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->whereRaw('products.name like ?', [
                '%' . $validated['productName'] . '%'
            ])
            ->groupBy('reviews.product_id')
            ->get();

        if (!empty($avgRating))
            return response()->json(['avgRating' => $avgRating], 200);
        else
            return response()->json(['message' => 'Средней оценки для данных товаров не найдено.']);
    }
    
    public function ratingCount(APIProductsRatingCountRequest $request)
    {
        $validated = $request->validated();

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

        if (!empty($ratingCount))
            return response()->json(['ratingCount' => $ratingCount], 200);
        else
            return response()->json(['message' => 'Количество оценок для товаров с таким запросом не найдено.']);
    }
}
