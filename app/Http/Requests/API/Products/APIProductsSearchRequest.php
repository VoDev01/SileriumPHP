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
            'productName' => ['max:100', 'string'],
            'sellerName' => ['max:50', 'nullable', 'string'],
            'reviewsCount' => ['boolean', 'nullable'],
            'searchKey' => ['ulid', 'nullable'],
            'redirect' => ['max:100', 'string', 'nullable'],
            'loadWith' => ['max:100', 'string', 'nullable']
        ];
    }
}