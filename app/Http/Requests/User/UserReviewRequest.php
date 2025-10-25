<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class UserReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'min:5', 'max:40'],
            'pros' => ['required', 'min:5', 'max:1500'],
            'cons' => ['required', 'min:5', 'max:1500'],
            'comment' => ['min:5', 'max:1500', 'nullable'],
            'rating' => ['required', 'numeric', 'min:1', 'max:10'],
            'product_id' => ['integer'],
            'review_images' => ['array', 'max:5', 'nullable'],
            'review_images.*' => [
                File::image()->min(10)->max(10000)->dimensions(
                    Rule::dimensions()
                        ->minWidth(128)->minHeight(128)->maxWidth(2500)->maxHeight(2500)
                )
            ],
        ];
    }
}
