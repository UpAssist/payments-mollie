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
     * @param $amount
     * @param string $description
     * @param string $redirectUrl
     * @param bool|false $persistPayment
     * @return mixed
     */
    public function create(
        $amount, $description = '', $redirectUrl = '', $persistPayment = false
    ) {
        return $this->paymentService->getPaymentLink(
            $amount, $description, $redirectUrl, $persistPayment
        );
    }
}