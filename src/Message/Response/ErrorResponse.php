<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

/**
 * Vipps Error Response
 */
class ErrorResponse extends Response
{
    public function isSuccessful()
    {
        return false;
    }

    public function getMessage()
    {
        return 'Not reserved, last operation: ' . $this->data['transactionLogHistory'][0]['operation'];
    }
}
