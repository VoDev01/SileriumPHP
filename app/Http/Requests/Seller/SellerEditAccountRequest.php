<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class SellerEditAccountRequest extends FormRequest
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
            "organization_name" => ["min:2", "max:75"],
            "nickname" => ["min:2", "max:75"],
            "organization_type" => ["string"],
            "tax_system" => ["string"],
            "organization_email" => ["min:5", "max:100", "email"],
            "email" => ["min:5", "max:100", "email", "exists:users,email"],
            "logo" => ["mime:png,jpg,jpeg", "dimensions:min_width=128,min_height=128,max_width=128,max_height=128", "nullable"]
        ];
    }
}
