<?php

namespace Kalimero\Casys\Service;

use Exception;
use SoapClient;
use Kalimero\Casys\Interfaces\RecurringPaymentInterface;

class RecurringPayment implements RecurringPaymentInterface
{
    /**
     * @var SoapClient
     */
    private SoapClient $soapClient;

    /**
     * RecurringPayment constructor.
     *
     * @param SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    /**
     * Perform a recurring payment.
     *
     * @param string $merchantID
     * @param string $rpRef
     * @param string $rpRefID
     * @param int $amount
     * @param string $password
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
                return $response->Success
                    ? ['success' => true, 'payment_reference' => $response->CPayPaymentRef ?? null]
                    : ['success' => false, 'error_description' => $response->ErrorDecription ?? 'Unknown error'];
            }

            return ['success' => false, 'error_description' => 'Invalid response format from SOAP service'];
        } catch (Exception $e) {
            return ['success' => false, 'error_description' => $e->getMessage()];
        }
    }
}
