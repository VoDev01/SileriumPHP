<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class APIProductsRequest extends FormRequest
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
            'name' => ['min:5', 'max:100', 'required'],
            'description' => ['min:50', 'max:1000'],
            'priceRub' => ['numeric', 'required'],
            'stockAmount' => ['max_digits:11', 'required'],
            'available' => ['boolean', 'required'],
            'subcategory_id'=> ['required']
        ];
    }
}
