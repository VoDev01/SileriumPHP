<?php

namespace App\Http\Requests\API\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class APIDeleteReviewRequest extends FormRequest
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
            'id' => ['ulid', 'required']
        ];
    }
}
