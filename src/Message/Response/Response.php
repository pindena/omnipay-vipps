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
        return isset($this->data['transactionInfo']['status']) &&
            in_array($this->data['transactionInfo']['status'], ['RESERVED', 'SALE', 'Captured', 'CAPTURED']);
    }

    public function isCancelled()
    {
        if (isset($this->data[0]['errorCode'])) {
            if ($this->getMessage() == 'Not reserved, last operation: CANCEL') {
                return true;
            }

            return false;
        }

        return isset($this->data['transactionInfo']['status']) &&
            in_array($this->data['transactionInfo']['status'], ['Cancelled', 'CANCELLED', 'REJECTED']);
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
