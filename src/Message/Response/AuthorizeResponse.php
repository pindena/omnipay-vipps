<?php


namespace Pindena\Omnipay\Vipps\Message\Response;


class AuthorizeResponse extends Response
{
    public function isSuccessful()
    {
        return isset($this->data['orderId'], $this->data['url']) && $this->getStatusCode() < 400;
    }
}