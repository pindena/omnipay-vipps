<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\AuthorizeResponse;

/**
 * Authorize request
 *
 * This request initiates a payment.
 */
class AuthorizeRequest extends Request
{
    public function getData()
    {
        $data = [
            'customerInfo' => [
                'mobileNumber' => $this->getPhone(),
            ],
            'merchantInfo' => [
                'isApp' => false,
                'fallBack' => $this->getReturnUrl(),
                'callbackPrefix' => $this->getNotifyUrl(),
                'paymentType' => 'eComm Regular Payment',
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
            ],
            'transaction' => [
                'amount' => intval($this->getAmount() * 100),
                'orderId' => $this->getTransactionId(),
                'timeStamp' => date('c'),
                'transactionText' => $this->getDescription(),
            ],
        ];

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->postRequest("/ecomm/v2/payments", [
            'Authorization' => $this->getAccessToken(),
        ], $data);

        return $this->response = new AuthorizeResponse($this, $response);
    }
}
