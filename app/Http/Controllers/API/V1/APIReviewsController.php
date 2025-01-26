<?php

namespace App\Http\Controllers\API\V1;

use Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\API\Reviews\APIDeleteReviewRequest;
use App\Http\Requests\API\Reviews\APIUpdateReviewRequest;
use App\Http\Requests\API\Reviews\APIUserReviewsSearchRequest;
use App\Http\Requests\API\Reviews\APIProductReviewsSearchRequest;

class APIReviewsController extends Controller
{
    
    public function index(Request $request)
    {
        $reviews = SearchFormPaginateResponseService::paginate($request, 'products', 15) ?? Review::paginate(15);
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
        $reviews = User::with('reviews')->where('ulid', $validated['userId'])->orWhere('email', $validated['userEmail'])->get()->first()->reviews;
        if($reviews != null)
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов запрашиваемого пользователя не найдено.'], 404);
    }
    public function searchProductReviews(APIProductReviewsSearchRequest $request)
    {
        $validated = $request->validated();
        $sellers = Seller::where('nickname', 'like', '%'.$validated['sellerName'].'%')->get();
        $products = Product::with(['reviews', 'seller'])->where('name', 'like', '%'.$validated['productName'].'%')->get();
        foreach($products as $product)
        {
            foreach($sellers as $seller)
            {
                if($product->seller->ulid == $seller->ulid)
                {
                    $products = $product;
                    break;
                }
            }
        }
        $reviews = $products->reviews;
        if($reviews != null)
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов на запрашиваемый товар не найдено.'], 404);
    }
}
