<?php

namespace App\Http\Requests\API\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class APIUpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['ulid', 'required'],
            'title' => ['min:5', 'max:40', 'nullable', 'string'],
            'pros' => ['min:5', 'max:1500', 'nullable', 'string'],
            'cons' => ['min:5', 'max:1500', 'nullable', 'string'],
            'comment' => ['min: 5', 'max:500', 'nullable', 'string'],
            'rating' => ['integer', 'nullable'],
        ];
    }
}
