<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class RefundRequest extends Request
{
    public function getData()
    {
        $data = [
            'merchantInfo' => [
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
            ],
            'transaction' => [
                'amount' => intval($this->getAmount() * 100),
                'transactionText' => $this->getDescription(),
            ]
        ];

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $orderId = $this->getTransactionReference();

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/refund", [
            'Authorization' => $this->getAccessToken(),
            'X-Request-Id' => uniqid('', true),
        ], $data);

        return $this->response = new Response($this, $response);
    }
}
