<?php

namespace Kalimero\Casys\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class handleRecurringPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'merchant_id' => 'required|string',
            'rp_ref' => 'required|string',
            'rp_ref_id' => 'required|string',
            'amount' => 'required|numeric',
            'password' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
