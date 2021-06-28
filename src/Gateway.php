<?php

namespace Pindena\Omnipay\Vipps;

use Omnipay\Common\AbstractGateway;
use Pindena\Omnipay\Vipps\Message\Request\VoidRequest;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;
use Pindena\Omnipay\Vipps\Message\Request\AuthorizeRequest;
use Pindena\Omnipay\Vipps\Message\Request\CompleteAuthorizeRequest;

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
            'serverUrl' => '',
            'testMode' => false,
            'clientSecret' => '',
            'ocpSubscription' => '',
            'merchantSerialNumber' => '',
        ];
    }

    public function setMerchantSerialNumber($value)
    {
        return $this->setParameter('merchantSerialNumber', $value);
    }

    public function getMerchantSerialNumber()
    {
        return $this->getParameter('merchantSerialNumber');
    }

    public function setOcpSubscription($value)
    {
        return $this->setParameter('ocpSubscription', $value);
    }

    public function getOcpSubscription()
    {
        return $this->getParameter('ocpSubscription');
    }

    public function setServerUrl($value)
    {
        return $this->setParameter('serverUrl', $value);
    }

    public function getServerUrl()
    {
        return $this->getParameter('serverUrl');
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
        return $this->createRequest(CompleteAuthorizeRequest::class, $parameters);
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
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    public function createCard(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function updateCard(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function deleteCard(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }
}
