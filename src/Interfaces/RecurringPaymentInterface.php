<?php

namespace Kalimero\Casys\Interfaces;

interface RecurringPaymentInterface
{
    /**
     * @param string $merchantID
     * @param string $rpRef
     * @param string $rpRefID
     * @param int $amount
     * @param string $password
     * @return array<string, mixed>
     */
    public function sendPayment(string $merchantID, string $rpRef, string $rpRefID, int $amount, string $password): array;
}