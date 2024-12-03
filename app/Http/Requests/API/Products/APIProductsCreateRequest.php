<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class APIProductsCreateRequest extends FormRequest
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
            'name' => ['min:5', 'max:100', 'string', 'required'],
            'description' => ['min:5', 'max:1000', 'string'],
            'priceRub' => ['numeric', 'required'],
            'productAmount' => ['numeric', 'max_digits:11', 'required'],
            'available' => ['boolean', 'required'],
            'subcategory_id'=> ['numeric', 'required'],
            'seller_id' => ['numeric', 'required']
        ];
    }
}
