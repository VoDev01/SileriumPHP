<?php

namespace App\Http\Requests\API\Profile;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class APIRegisterRequest extends FormRequest
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
        $rules = [
            'name' => ['min:2', 'max:30', 'required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password',
            'phone' => ['min:8', 'max:20', 'nullable'],
        ];
        if (app()->environment('testing'))
        {
            $rules['password'] = ['required', 'confirmed', Password::min(10)->numbers(), 'max:1000'];
            return $rules;
        }
        else
        {
            $rules['password'] = ['required', 'confirmed', Password::min(10)->numbers(), 'max:50'];
            return $rules;
        }
    }
}
