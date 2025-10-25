<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserBanRequest extends FormRequest
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
            'user_id' => ['string', 'max:500', 'required'],
            'admin_id' => ['ulid', 'required'],
            'reason' => ['min:4', 'max:500', 'required', 'string'],
            'duration' => ['numeric', 'required'],
            'timeType' => ['string'],
            'api_user' => ['boolean']
        ];
    }
}
