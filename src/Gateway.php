<?php

namespace Pindena\Omnipay\Vipps;

use Omnipay\Common\AbstractGateway;
use Pindena\Omnipay\Vipps\Message\Request\CancelRequest;
use Pindena\Omnipay\Vipps\Message\Request\RefundRequest;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;
use Pindena\Omnipay\Vipps\Message\Request\DetailsRequest;
use Pindena\Omnipay\Vipps\Message\Request\AuthorizeRequest;

/**
 * VippsOmnipay Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Vipps';
    }

    public function getDefaultParameters()
    {
        return [
            'clientId' => '',
            'clientSecret' => '',
            'ocpSubscription' => '',
            'merchantSerialNumber' => '',
            'testMode' => false,
        ];
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('clientSecret', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    public function setOcpSubscription($value)
    {
        return $this->setParameter('ocpSubscription', $value);
    }

    public function getOcpSubscription()
    {
        return $this->getParameter('ocpSubscription');
    }

    public function setMerchantSerialNumber($value)
    {
        return $this->setParameter('merchantSerialNumber', $value);
    }

    public function getMerchantSerialNumber()
    {
        return $this->getParameter('merchantSerialNumber');
    }

    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function completeAuthorize(array $parameters = [])
    {
        return $this->createRequest(DetailsRequest::class, $parameters);
    }

    public function capture(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function void(array $parameters = [])
    {
        return $this->createRequest(CancelRequest::class, $parameters);
    }
}
