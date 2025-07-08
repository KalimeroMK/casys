<?php

namespace Kalimero\Casys\Interfaces;

interface RecurringPaymentInterface
{
    /**
     * @return array<string, mixed>
     */
    public function sendPayment(string $merchantID, string $rpRef, string $rpRefID, int $amount, string $password): array;
}