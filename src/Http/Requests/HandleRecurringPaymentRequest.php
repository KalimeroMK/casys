<?php

namespace Kalimero\Casys\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandleRecurringPaymentRequest extends FormRequest
{
    /**
     * Define validation rules for the recurring payment request.
     *
     * @return array<string, string>
     */
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

    /**
     * Authorize the request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
