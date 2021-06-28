<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class CompleteAuthorizeRequest extends Request
{
    public function getData()
    {
        $data = [];

        $data['orderId'] = $this->getTransactionId();

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        list('orderId' => $orderId) = $data;

        $response = $this->getRequest("/ecomm/v2/payments/{$orderId}/details", [
            'Authorization' => $this->getAccessToken(),
            'X-Request-Id' => uniqid('', true),
        ]);

        return $this->response = new Response($this, $response);
    }
}
