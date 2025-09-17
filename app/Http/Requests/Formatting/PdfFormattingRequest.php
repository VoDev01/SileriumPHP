<?php

namespace App\Http\Requests\Formatting;

use Illuminate\Foundation\Http\FormRequest;

class PdfFormattingRequest extends FormRequest
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
            'pageHtml' => ['required', 'max: 1000000', 'string'],
            'tableHtml' => ['required', 'max: 10000', 'string'],
            'tableRowHtml' => ['required', 'max: 10000', 'string'],
            'insertAfterElement' => ['required', 'max:10000', 'string'],
            'data' => ['required', 'array'],
            'totalPages' => ['nullable', 'integer']
        ];
    }
}
