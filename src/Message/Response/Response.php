<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * VippsOmnipay Response
 *
 * This is the response class for all VippsOmnipay requests.
 *
 * @see \Pindena\Omnipay\Vipps\Gateway
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);

        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        if (isset($this->data['errorCode'])) {
            return false;
        }

        return isset($this->data['success']) || (isset($this->data['transactionInfo']['status']) && $this->getStatusCode() < 400);
    }

    public function isCancelled()
    {
        $isError = isset($this->data['errorCode']);
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

    public function getCardReference()
    {
        return isset($this->data['orderId']) ? $this->data['orderId'] : null;
    }

    public function getMessage()
    {
        if (isset($this->data['errorMessage'])) {
            return $this->data['errorMessage'];
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

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
