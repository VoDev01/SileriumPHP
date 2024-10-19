<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserReviewRequest;

class UserReviewController extends Controller
{
    public function userReviews()
    {
        $reviews = Review::with('images')->with('user')->with('product')->where('user_id', Auth::id())->paginate(5);
        return view('user.review.userreviews', ['reviews' => $reviews]);
    }
    public function review(int $productId)
    {
        $product = Product::find($productId);
        return view('user.review.reviewproduct', ['product' => $product]);
    }
    public function postReview(UserReviewRequest $request)
    {
        $validated = $request->validated();
        $review = ReviewService::make($validated, Auth::id(), $request->product_id, $request->review_images);
        return redirect()->route('userReviews');
    }
    public function editReview(Review $review)
    {
        return view('user.review.reviewproduct', ['review' => $review, 'product' => $review->product]);
    }
    public function postEditReview(UserReviewRequest $request)
    {
        $validated = $request->validated();
        ReviewService::update($request->review_id, $validated, $request->product_id, Auth::id());
        return redirect()->route('userReviews');
    }
    public function deleteReview(Request $request)
    {
        Review::destroy($request->review_id);
        return redirect()->route('userReviews');
    }
}
