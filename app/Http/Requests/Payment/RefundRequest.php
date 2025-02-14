<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
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
            'paymentId' => ['required', 'exists:payments,payment_id'],
            'products' => ['required', 'exists:products,ulid'],
            'orderId' => ['required', 'ulid', 'exists:orders,ulid']
        ];
    }
}
