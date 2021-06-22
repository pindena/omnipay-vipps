<?php

namespace Pindena\Omnipay\Vipps;

use Omnipay\Common\AbstractGateway;
use Pindena\Omnipay\Vipps\Message\CreditCardRequest;
use Pindena\Omnipay\Vipps\Message\CompletePurchaseRequest;
use Pindena\Omnipay\Vipps\Message\TransactionReferenceRequest;

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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest(CreditCardRequest::class, $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(CreditCardRequest::class, $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(TransactionReferenceRequest::class, $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest(TransactionReferenceRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(TransactionReferenceRequest::class, $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest(TransactionReferenceRequest::class, $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest(CreditCardRequest::class, $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest(CreditCardRequest::class, $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest(CreditCardRequest::class, $parameters);
    }
}
