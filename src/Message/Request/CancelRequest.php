<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class CancelRequest extends Request
{
    public function getData()
    {
        $data = [
            'merchantInfo' => [
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
            ],
            'transaction' => [
                'transactionText' => $this->getDescription(),
            ],
        ];

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $orderId = $this->getTransactionReference();

        $response = $this->putRequest("/ecomm/v2/payments/{$orderId}/cancel", [
            'Authorization' => $this->getAccessToken(),
        ], $data);

        return $this->response = new Response($this, $response);
    }
}
