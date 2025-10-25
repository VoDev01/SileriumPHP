<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class APIProductsSearchRequest extends FormRequest
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
            'productName' => ['min:1', 'max:100', 'string'],
            'sellerName' => ['max:50', 'nullable', 'string'],
            'reviewsCount' => ['boolean', 'nullable'],
            'redirect' => ['max:100', 'string', 'nullable'],
            'loadWith' => ['max:100', 'string', 'nullable'],
            'sellerId' => ['integer', 'nullable'],
            'sortOrder' => ['integer', 'nullable'],
            'page' => ['integer', 'nullable']
        ];
    }
}