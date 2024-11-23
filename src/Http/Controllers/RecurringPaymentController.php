<?php

namespace Kalimero\Casys\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Kalimero\Casys\Http\Requests\handleRecurringPaymentRequest;
use Kalimero\Casys\Http\Service\RecurringPayment;

class RecurringPaymentController extends Controller
{
    protected RecurringPayment $recurringPayment;

    public function __construct(RecurringPayment $recurringPayment)
    {
        $this->recurringPayment = $recurringPayment;
    }

    /**
     * Handle recurring payment request.
     */
    public function handleRecurringPayment(handleRecurringPaymentRequest $request)
    {
        try {
            $validated = $request->validated();
            $response = $this->recurringPayment->sendPayment(
                $validated['merchant_id'],
                $validated['rp_ref'],
                $validated['rp_ref_id'],
                $validated['amount'],
                $validated['password']
            );

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
