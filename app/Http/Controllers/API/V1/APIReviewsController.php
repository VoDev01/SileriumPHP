<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Reviews\APIDeleteReviewRequest;
use App\Http\Requests\API\Reviews\APIUpdateReviewRequest;
use App\Http\Requests\API\Reviews\APIUserReviewsSearchRequest;
use App\Http\Requests\API\Reviews\APIProductReviewsSearchRequest;
use App\Http\Requests\API\Reviews\APIProductsAverageRatingRequest;
use App\Http\Requests\API\Reviews\APIProductsRatingCountRequest;
use App\Repositories\ReviewRepository;
use App\Services\SearchForms\ReviewSearchFormService;
use App\Services\User\ReviewService;

class APIReviewsController extends Controller
{

    public function index(int $itemsPerPage = 15)
    {
        $reviews = (new ReviewRepository)->index($itemsPerPage);
        return response()->json(['reviews' => $reviews]);
    }

    public function show(Request $request)
    {
        $review = (new ReviewRepository)->show($request->id);
        return response()->json(['review' => $review]);
    }

    public function update(APIUpdateReviewRequest $request)
    {
        $validated = $request->validated();
        $review = (new ReviewRepository)->update($validated);

        return response()->json();
    }

    public function delete(APIDeleteReviewRequest $request)
    {
        $validated = $request->validated();

        if(!(new ReviewRepository)->delete($validated['id']))
            return response()->json(['message' => 'Отзыва с таким id не найдено', 404]);
        else
            return response()->json();
    }

    public function searchUserReviews(APIUserReviewsSearchRequest $request)
    {
        $validated = $request->validated();

        $reviews = (new ReviewSearchFormService)->searchUserReviews($validated);

        if ($reviews != null)
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов запрашиваемого пользователя не найдено.'], 404);
    }
    public function searchProductReviews(APIProductReviewsSearchRequest $request)
    {
        $validated = $request->validated();

        $reviews = (new ReviewSearchFormService)->searchProductReviews($validated);

        if (!empty($reviews))
            return response()->json(['reviews' => $reviews]);
        else
            return response()->json(['message' => 'Отзывов на запрашиваемый товар не найдено.'], 404);
    }

    public function averageRating(APIProductsAverageRatingRequest $request)
    {
        $validated = $request->validated();

        $avgRating = (new ReviewService)->ratingCount($validated);

        if (!empty($avgRating))
            return response()->json(['avgRating' => $avgRating], 200);
        else
            return response()->json(['message' => 'Средней оценки для данных товаров не найдено.']);
    }

    public function ratingCount(APIProductsRatingCountRequest $request)
    {
        $validated = $request->validated();

        $ratingCount = (new ReviewService)->ratingCount($validated);

        if (!empty($ratingCount))
            return response()->json(['ratingCount' => $ratingCount], 200);
        else
            return response()->json(['message' => 'Количество оценок для товаров с таким запросом не найдено.']);
    }
}
