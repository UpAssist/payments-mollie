<?php
namespace UpAssist\Payments\Mollie\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use UpAssist\Payments\Mollie\Service\PaymentService;

/**
 * Class PaymentController
 *
 * @package UpAssist\Payments\Mollie\Controller
 */
class PaymentController extends ActionController
{
    /**
     * PaymentService
     *
     * @Flow\Inject
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * Create a payment
     *
     * @api
     * @param float $amount
     * @param string $description
     * @param string $redirectUrl
     * @param boolean $persistPayment
     * @return string
     */
    public function createAction(
        $amount, $description = null, $redirectUrl = null, $persistPayment = false
    ) {
        if ($redirectUrl === null) {
            $redirectUrl =  $this->uriBuilder
                ->setCreateAbsoluteUri(true)->uriFor('success');
        }
        return $this->paymentService->getPaymentLink(
            $amount, $description, $redirectUrl, $persistPayment
        );
    }

    /**
     * @return boolean
     */
    public function successAction()
    {
        return true;
    }
}