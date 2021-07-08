<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class CancelRequest extends Request
{
    public function getData()
    {
        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $orderId = $this->getTransactionReference();

        $response = $this->putRequest("/ecomm/v2/payments/{$orderId}/cancel", [
            'Authorization' => $this->getAccessToken(),
        ]);

        return $this->response = new Response($this, $response);
    }
}
