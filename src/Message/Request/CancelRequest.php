<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class CancelRequest extends Request
{
    public function getData()
    {
        $data = [];

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        $orderId = $this->getTransactionReference();

        return $this->response = new Response($this, $this->putRequest("/ecomm/v2/payments/{$orderId}/cancel"));
    }
}
