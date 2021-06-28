<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Pindena\Omnipay\Vipps\Message\Response\Response;

class VoidRequest extends Request
{
    public function getData()
    {
        // ..
    }

    public function sendData($data): \Omnipay\Common\Message\ResponseInterface
    {
        return $this->response = new Response($this, $this->getRequest("/ecomm/v2/payments/{$this->getTransactionReference()}/cancel"));
    }
}