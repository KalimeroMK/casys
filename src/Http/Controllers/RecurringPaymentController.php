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
     *
     * @param HandleRecurringPaymentRequest $request
     * @return JsonResponse
     */
    public function handleRecurringPayment(HandleRecurringPaymentRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'merchant_id' => 'required|string',
            'rp_ref' => 'required|string',
            'rp_ref_id' => 'required|string',
            'amount' => 'required|integer',
            'password' => 'required|string',
        ]);

        $response = $this->recurringPayment->sendPayment(
            $validated['merchant_id'],
            $validated['rp_ref'],
            $validated['rp_ref_id'],
            $validated['amount'],
            $validated['password']
        );

        return response()->json($response);
    }
}
