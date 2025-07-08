<?php

namespace Kalimero\Casys\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Kalimero\Casys\Http\Requests\HandleRecurringPaymentRequest;
use Kalimero\Casys\Interfaces\RecurringPaymentInterface;

class RecurringPaymentController extends Controller
{
    protected RecurringPaymentInterface $recurringPayment;

    public function __construct(RecurringPaymentInterface $recurringPayment)
    {
        $this->recurringPayment = $recurringPayment;
    }

    /**
     * Handle the recurring payment request.
     */
    public function handleRecurringPayment(HandleRecurringPaymentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $merchantId = is_string($validated['merchant_id']) ? $validated['merchant_id'] : '';
        $rpRef = is_string($validated['rp_ref']) ? $validated['rp_ref'] : '';
        $rpRefId = is_string($validated['rp_ref_id']) ? $validated['rp_ref_id'] : '';
        $amount = is_int($validated['amount']) ? $validated['amount'] : 0;
        $password = is_string($validated['password']) ? $validated['password'] : '';

        $response = $this->recurringPayment->sendPayment(
            $merchantId,
            $rpRef,
            $rpRefId,
            $amount,
            $password
        );

        return response()->json($response);
    }
}
