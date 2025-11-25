<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminAssignRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::inspect('accessAdmin', Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'role' => ['required', 'array', 'distinct',],
            'role.*' => ['string', 'max:50',  'exists:roles,role'],
            'user' => ['string', 'max:50', 'email', 'exists:users,email']
        ];
    }
}
