<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

class DetailsResponse extends Response
{
    public function isSuccessful()
    {
        return isset($this->data['transactionSummary']) && isset($this->data['transactionLogHistory']);
    }
}
