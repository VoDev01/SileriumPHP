<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class APIProductsUpdateRequest extends FormRequest
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
            'id' => ['required', 'ulid'],
            'name' => ['min:2', 'max:100', 'nullable', 'string'],
            'description' => ['min:5', 'max:1000', 'string', 'nullable'],
            'priceRub' => ['numeric', 'nullable'],
            'available' => ['boolean'],
            'productAmount' => ['numeric', 'nullable'],
            'subcategory_id' => ['integer']
        ];
    }
}
