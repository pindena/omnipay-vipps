<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;
use Pindena\Omnipay\Vipps\Message\Response\ErrorResponse;

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

        $details = $this->getDetails($orderId);

        if (! in_array($details['transactionLogHistory'][0]['operation'], ['RESERVE', 'RESERVED'])) {
            return $this->response = new ErrorResponse($this, $details);
        }

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/capture", [
            'Authorization' => $this->getAccessToken(),
        ], $data);

        return $this->response = new Response($this, $response);
    }
}
