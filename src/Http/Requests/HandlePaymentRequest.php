<?php

declare(strict_types=1);

namespace Kalimero\Casys\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandlePaymentRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
