<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function userReviews()
    {
        $reviews = Review::with('images')->with('user')->with('product')->where('user_id', Auth::id())->paginate(5);
        return view('user.review.userreviews', ['reviews' => $reviews]);
    }
    public function review(Product $product)
    {
        return view('user.review.reviewproduct', ['product' => $product]);
    }
    public function postReview(Request $request)
    {
        if ($request->review_images != null)
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:5', 'max:40'],
                'pros' => ['required', 'min:5', 'max:1500'],
                'cons' => ['required', 'min:5', 'max:1500'],
                'comment' => ['min:5', 'max:1500', 'nullable'],
                'rating' => ['required'],
                'review_images' => ['array', 'max:5'],
                'review_images.*' => [File::image()->min('1kb')->max('15mb')->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500))]
            ]);
        else
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:5', 'max:40'],
                'pros' => ['required', 'min:5', 'max:1500'],
                'cons' => ['required', 'min:5', 'max:1500'],
                'comment' => ['min:5', 'max:1500', 'nullable'],
                'rating' => ['required']
            ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        $review = Review::create([
            'title' => $validated['title'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
        if ($request->review_images != null) {
            for ($i = 0; $i < $validated['review_images']->count(); $i++) {
                $review->images()->create([
                    'imagePath' => $validated['review_images'][$i],
                    'review_id' => $review->id
                ]);
            }
        }
        $review->save();
        return redirect()->route('userReviews');
    }
    public function editReview(Review $review)
    {
        return view('user.review.reviewproduct', ['review' => $review, 'product' => $review->product]);
    }
    public function postEditReview(Request $request)
    {
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
        return redirect()->route('userReviews');
    }
    public function deleteReview(Request $request)
    {
        Review::destroy($request->review_id);
        return redirect()->route('userReviews');
    }
}
