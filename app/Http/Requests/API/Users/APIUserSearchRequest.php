<?php

namespace App\Http\Requests\API\Users;

use Illuminate\Foundation\Http\FormRequest;

class APIUserSearchRequest extends FormRequest
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
            'id' => ['ulid', 'nullable'],
            'email' => ['min:2', 'email', 'max:100'],
            'name' => ['max:100', 'nullable', 'string', 'nullable'],
            'surname' => ['max:100', 'nullable', 'string', 'nullable'],
            'phone' => ['numeric', 'min_digits:8', 'max_digits:15', 'nullable'],
            'redirect' => ['max:100', 'nullable', 'string'],
            'loadWith' => ['max:100', 'nullable', 'string']
        ];
    }
}
