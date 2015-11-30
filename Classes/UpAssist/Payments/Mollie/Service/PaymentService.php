<?php
namespace UpAssist\Payments\Mollie\Service;

/**
 * Class PaymentService
 *
 */

use \Mollie_API_Client;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;

/**
 * Class PaymentService
 *
 * @package UpAssist\Payments\Mollie\Service
 * @author Henjo Hoeksma <henjo@upassist.com>
 */
class PaymentService
{
    /**
     * Mollie API Client
     *
     * @var \Mollie_API_Client
     */
    protected $mollie;

    /**
     * Mollie API Key
     *
     * @Flow\Inject(setting="apiKey", package="UpAssist.Payments.Mollie")
     *
     * @var string
     */
    protected $apiKey;

    /**
     * PaymentService constructor.
     */
    public function __construct()
    {
        $this->mollie = new \Mollie_API_Client();
        if ($this->apiKey) {
            $this->mollie->setApiKey($this->apiKey);
        } else {
            throw new Exception('ApiKey is not set', 1448890896);
        }
    }

    /**
     * Create a payment
     *
     * @param float $amount
     * @param string $description
     * @param string $redirectUrl
     * @param boolean $persistPayment
     * @return \Mollie_API_Object_Payment
     * @throws Exception
     */
    public function createPayment(
        $amount, $description, $redirectUrl, $persistPayment = false
    ) {
        $paymentData = [
            'amount' => $amount,
            'description' => $description,
            'redirectUrl' => $redirectUrl
        ];

        /**
         * The payment object
         *
         * @var \Mollie_API_Object_Payment $payment
         */
        $payment = $this->mollie->payments->create($paymentData);

        if ($payment && $persistPayment === false) {
            return $payment;
        }

        if ($payment && $persistPayment === true) {
            throw new Exception(
                'Saving payments is not yet implemented in this version',
                1448891346
            );
        }
    }


    /**
     * Get a payment url
     *
     * @param float $amount
     * @param string $description
     * @param string $redirectUrl
     * @param boolean $persistPayment
     * @return mixed
     */
    public function getPaymentLink(
        $amount, $description = '', $redirectUrl = '', $persistPayment = false
    ) {
        try {
            $payment = $this->createPayment(
                $amount, $description, $redirectUrl, $persistPayment
            );

            return $payment->getPaymentUrl();

        } catch (Exception $e) {
            throw new $e('Something went wrong creating a payment url', 1448892637);
        }
    }
}
