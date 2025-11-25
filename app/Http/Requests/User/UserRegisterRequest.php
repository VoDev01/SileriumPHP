<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'surname' => ['min:2', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password',
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:2', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:30', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'dimensions:min_width=128,min_height=128,max_width=128,max_height=128', 'size:' . 150 * 1000000, 'nullable']
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
