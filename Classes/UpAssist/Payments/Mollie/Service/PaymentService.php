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
 * @Flow\Scope("singleton")
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
     * @var string
     */
    protected $apiKey;

    /**
     * Default description
     * @Flow\Inject(setting="default.description", package="UpAssist.Payments.Mollie")
     * @var string
     */
    protected $defaultDescription;

    /**
     * Default redirectUrl
     * @Flow\Inject(setting="default.redirectUrl", package="UpAssist.Payments.Mollie")
     * @var string
     */
    protected $defaultRedirectUrl;

    /**
     * PaymentService constructor.
     */
    public function __construct()
    {
        $this->mollie = new \Mollie_API_Client();
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
        $amount, $description = null, $redirectUrl = null, $persistPayment = false
    ) {

        if ($description === null) {
            $description = $this->defaultDescription;
        }

        if ($redirectUrl === null) {
            $redirectUrl = $this->defaultRedirectUrl;
        }

        if ($this->apiKey) {
            $this->mollie->setApiKey($this->apiKey);
        } else {
            throw new Exception('ApiKey is not set', 1448890896);
        }

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
        $amount, $description = null, $redirectUrl = null, $persistPayment = false
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
