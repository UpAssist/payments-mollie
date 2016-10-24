<?php
namespace UpAssist\Payments\Mollie\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
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
        $amount = null,
        $description = null,
        $redirectUrl = null,
        $persistPayment = false
    ) {
        $payment = $this->request->hasArgument('payment')
            ? $this->request->getArgument('payment')
            : null;
        if ($payment) {
            if (!is_array($payment['amount'])) {
                // If a comma is found, replace it by a dot
                $amount = str_replace(',', '.', $payment['amount']);
            } else {
                $amount = $payment['amount'][0] . '.' . $payment['amount'][1];
            }

            $description = isset($payment['description'])
                ? $payment['description']
                : $description;
            $redirectUrl = isset($payment['redirectUrl'])
                ? $payment['redirectUrl']
                : $redirectUrl;
        }
        if ($redirectUrl === null) {
            $redirectUrl =  $this->uriBuilder
                ->setCreateAbsoluteUri(true)->uriFor('success');
        }
        $this->redirectToUri(
            $this->paymentService->getPaymentLink(
                $amount, $description, $redirectUrl, $persistPayment
            )
        );
    }

    /**
     * @return boolean
     */
    public function successAction()
    {
        $this->flashMessageContainer->addMessage(
            new Message(
                $message = 'Payment was successful',
                $code = null,
                $arguments = [],
                $severity = Message::SEVERITY_OK
            )
        );
    }
}
