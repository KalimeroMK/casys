<?php

namespace Kalimero\Casys\Http\Service;

use SoapClient;
use Exception;

class RecurringPayment
{
    protected SoapClient $soapClient;

    public function __construct()
    {
        $this->soapClient = new SoapClient('https://www.cpay.com.mk/Recurring/RecurringPaymentsWS.wsdl');
    }

    /**
     * Perform a recurring payment.
     *
     * @param string $merchantID
     * @param string $rpRef
     * @param string $rpRefID
     * @param int $amount
     * @param string $password
     * @return array
     * @throws Exception
     */
    public function sendPayment(string $merchantID, string $rpRef, string $rpRefID, int $amount, string $password): array
    {
        $md5 = md5($merchantID . $rpRefID . $rpRef . $amount . $password);

        $response = $this->soapClient->__soapCall('sendPayment', [
            'Amount' => $amount,
            'MerchantID' => $merchantID,
            'RPRef' => $rpRef,
            'RPRefID' => $rpRefID,
            'MD5' => $md5,
        ]);

        if ($response->Success) {
            return [
                'success' => true,
                'payment_reference' => $response->CPayPaymentRef,
            ];
        }

        return [
            'success' => false,
            'error_description' => $response->ErrorDecription ?? 'Unknown error',
        ];
    }
}
