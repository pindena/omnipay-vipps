<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Pindena\Omnipay\Vipps\Message\Response\Response;

class VoidRequest extends Request
{
    public function getData()
    {
        $data = [];

        $data['orderId'] = $this->getTransactionReference();

        return $data;
    }

    public function sendData($data): \Omnipay\Common\Message\ResponseInterface
    {
        list('orderId' => $orderId) = $data;

        return $this->response = new Response($this, $this->putRequest("/ecomm/v2/payments/{$orderId}/cancel"));
    }
}