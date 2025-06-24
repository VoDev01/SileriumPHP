<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;

class UserLoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:users,email', 'min: 5', 'max: 100'],
            'password' => ['required', RulesPassword::min(10)->numbers(), 'max:50'],
            'remember_me' => ['boolean'],
            'api' => ['nullable', 'string']
        ];
    }
}
