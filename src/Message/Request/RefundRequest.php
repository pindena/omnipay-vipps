<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Pindena\Omnipay\Vipps\Message\Response\Response;

class RefundRequest extends Request
{
    public function getData()
    {
        $data = [];

        $data['token'] = $this->getAccessToken();
        $data['amount'] = intval($this->getAmount() * 100);
        $data['orderId'] = $this->getTransactionReference();

        return $data;
    }

    public function sendData($data): \Omnipay\Common\Message\ResponseInterface
    {
        list('orderId' => $orderId, 'token' => $token, 'amount' => $amount, 'transactionText' => $transactionText) = $data;

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/refund", [
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