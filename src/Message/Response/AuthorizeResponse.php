<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

class AuthorizeResponse extends Response
{
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        return isset($this->data['transactionInfo']['status']) &&
            in_array($this->data['transactionInfo']['status'], ['RESERVED', 'SALE']);
    }
}
