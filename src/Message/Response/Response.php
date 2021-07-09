<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Response
 *
 * This is the response class for most requests.
 *
 * @see \Pindena\Omnipay\Vipps\Gateway
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['transactionInfo']['status']);
    }

    public function isCancelled()
    {
        $isError = isset($this->data[0]['errorCode']);
        $status = isset($this->data['transactionInfo']['status']) ? $this->data['transactionInfo']['status'] : null;

        if (! $status) {
            return false;
        }

        return $isError || $status == 'Cancelled';
    }

    public function getTransactionReference()
    {
        return isset($this->data['orderId']) ? $this->data['orderId'] : null;
    }

    public function getTransactionId()
    {
        return isset($this->data['orderId']) ? $this->data['orderId'] : null;
    }

    public function getMessage()
    {
        if (isset($this->data[0]['errorMessage'])) {
            return $this->data[0]['errorMessage'];
        }

        return isset($this->data['message']) ? $this->data['message'] : null;
    }

    public function isRedirect()
    {
        return (bool) $this->getRedirectUrl();
    }

    public function getRedirectUrl()
    {
        return isset($this->data['url'])
            ? $this->data['url']
            : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
