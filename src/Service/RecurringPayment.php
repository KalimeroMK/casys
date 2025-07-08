<?php

namespace Kalimero\Casys\Service;

use Exception;
use SoapClient;
use Kalimero\Casys\Interfaces\RecurringPaymentInterface;

class RecurringPayment implements RecurringPaymentInterface
{
    private SoapClient $soapClient;

    /**
     * RecurringPayment constructor.
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    /**
     * Perform a recurring payment.
     *
     * @return array{success: bool, payment_reference?: string, error_description?: string}
     * @throws Exception
     */
    public function sendPayment(string $merchantID, string $rpRef, string $rpRefID, int $amount, string $password): array
    {
        $params = [
            'Amount' => $amount,
            'MerchantID' => $merchantID,
            'RPRef' => $rpRef,
            'RPRefID' => $rpRefID,
            'MD5' => md5($merchantID . $rpRefID . $rpRef . $amount . $password),
        ];

        try {
            $response = $this->soapClient->__soapCall('sendPayment', [$params]);

            if (is_object($response) && isset($response->Success)) {
                $paymentReference = '';
                if (isset($response->CPayPaymentRef)) {
                    $paymentReference = is_string($response->CPayPaymentRef) ? $response->CPayPaymentRef : '';
                }

                $errorDescription = 'Unknown error';
                if (isset($response->ErrorDecription)) {
                    $errorDescription = is_string($response->ErrorDecription) ? $response->ErrorDecription : 'Unknown error';
                }

                return $response->Success
                    ? ['success' => true, 'payment_reference' => $paymentReference]
                    : ['success' => false, 'error_description' => $errorDescription];
            }

            return ['success' => false, 'error_description' => 'Invalid response format from SOAP service'];
        } catch (Exception $e) {
            return ['success' => false, 'error_description' => $e->getMessage()];
        }
    }
}
