<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Facades\ReviewServiceFacade as ReviewService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserReviewRequest;
use Illuminate\Support\Facades\Validator;

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
<<<<<<< Updated upstream
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:5', 'max:40'],
            'pros' => ['required', 'min:5', 'max:1500'],
            'cons' => ['required', 'min:5', 'max:1500'],
            'comment' => ['min:5', 'max:1500', 'nullable'],
            'review_images' => ['array', 'max:5', 'nullable'],
            'review_images.*' => File::image()->min('1kb')->max('15mb')->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500))
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        Review::find($request->review_id)->update([
            'title' => $validated['title'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'comment' => $validated['comment'],
            'review_images' => $validated['review_images'],
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
=======
        $validated = $request->validated();
        ReviewService::update($request->review_id, $validated, $request->product_id, Auth::id());
>>>>>>> Stashed changes
        return redirect()->route('userReviews');
    }
    public function deleteReview(Request $request)
    {
        Review::destroy($request->review_id);
        return redirect()->route('userReviews');
    }
}
