<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class CaptureRequest extends Request
{
    public function getData()
    {
        $data = [];

        $data['token'] = $this->getAccessToken();
        $data['transactionText'] = $this->getDescription();
        $data['orderId'] = $this->getTransactionReference();
        $data['amount'] = intval($this->getAmount() * 100);

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        list('amount' => $amount, 'token' => $token, 'transactionText' => $transactionText, 'orderId' => $orderId) = $data;

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/capture", [
            'Authorization' => $token,
            'X-Request-Id' => uniqid('', true),
        ], [
            'merchantInfo' => [
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
            ],
            'transaction' => [
                'amount' => $amount,
                'transactionText' => $transactionText,
            ]
        ]);

        return $this->response = new Response($this, $response);
    }
}
