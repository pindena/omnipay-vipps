<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * VippsOmnipay Response
 *
 * This is the response class for all VippsOmnipay requests.
 *
 * @see \Pindena\Omnipay\Vipps\Gateway
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['success']) && $this->data['success'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getTransactionId()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getCardReference()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? $this->data['message'] : null;
    }
}
