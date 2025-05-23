<?php

namespace App\Http\Requests\API\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class APIProductsRatingCountRequest extends FormRequest
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
            'productName' => ['min:5', 'max:100', 'string', 'required']
        ];
    }
}
