<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\ReviewRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEditReviewRequest;
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
        $review = (new ReviewRepository)->create($validated, Auth::id());
        return redirect()->route('userReviews');
    }
    public function editReview(Review $review)
    {
        return view('user.review.reviewproduct', ['review' => $review, 'product' => $review->product]);
    }
    public function postEditReview(UserEditReviewRequest $request)
    {
        $validated = $request->validated();
        (new ReviewRepository)->update($validated);
        return redirect()->route('userReviews');
    }
    public function deleteReview(Request $request)
    {
        Review::destroy($request->review_id);
        return redirect()->route('userReviews');
    }
}
