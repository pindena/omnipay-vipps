<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

/**
 * Capture request
 *
 * This request charges the amount from the buyer's account.
 */
class CaptureRequest extends Request
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

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/capture", [
            'Authorization' => $this->getAccessToken(),
        ], $data);

        return $this->response = new Response($this, $response);
    }
}
