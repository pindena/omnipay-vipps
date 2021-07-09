<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

class RefundResponse extends Response
{
    public function isSuccessful()
    {
        return isset($this->data['transaction']['status']);
    }
}
