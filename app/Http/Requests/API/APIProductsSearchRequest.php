<?php

namespace App\Http\Requests\API;

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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'seller_name' => ['min:5', 'max:50', 'required'],
            'product_name' => ['min:5', 'max:100', 'required']
        ];
    }
}
