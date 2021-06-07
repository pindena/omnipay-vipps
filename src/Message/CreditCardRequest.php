<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * VippsOmnipay Authorize/Purchase Request
 *
 * This is the request that will be called for any transaction which submits a credit card,
 * including `authorize` and `purchase`
 */
class CreditCardRequest extends Request
{
    protected $zeroAmountAllowed = false;

    public function getData()
    {
        $order_id = $this->getTransactionId();
        $access_token = $this->getAccessToken();

        $transactionText = $this->getDescription();
        $phone = $this->getCard()->getNumber();
        $amount = intval($this->getAmount() * 100);

        $payment = $this->createPayment($order_id, $access_token, $amount, $phone);

        $data = [];

        $data['phone'] = $phone;
        $data['amount'] = $amount;
        $data['order_id'] = $order_id;
        $data['url'] = $payment['url'] ?? '';
        $data['access_token'] = $access_token;
        $data['transactionText'] = $transactionText;

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $data['reference'] = uniqid();
        $data['success'] = 0 === substr($this->getCard()->getNumber(), -1, 1) % 2;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }

    public function createPayment($orderId, $access_token, $transaction_amount, $customer_number)
    {
        $response = $this->postRequest($this->getBaseUrl("/ecomm/v2/payments"), [
            'Authorization' => $access_token,
        ], [
            'customerInfo' => [
                'mobileNumber' => $customer_number,
            ],
            'merchantInfo' => [
                'isApp' => false,
                'fallBack' => $this->getReturnUrl(),
                'paymentType' => 'eComm Regular Payment',
                'callbackPrefix' => $this->getNotifyUrl(),
                'consentRemovalPrefix' => "{$this->getServerUrl()}/vipps",
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
                'shippingDetailsPrefix' => "{$this->getServerUrl()}/gateways/VippsOmnipay/authorize?a=shipping",
            ],
            'transaction' => [
                'amount' => $transaction_amount,
                'orderId' => $orderId,
                'timeStamp' => date('c'),
                'transactionText' => $this->getDescription(),
            ],
        ]);

        return $response;
    }
}