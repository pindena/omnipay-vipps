<?php
namespace CoreTrekStein\VippsOmnipay;

use Omnipay\Common\AbstractGateway;

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
        return array(
            'base_url'             => '',
            'client_id'            => '',
            'client_secret'        => '=',
            'merchantSerialNumber' => '',
            'ocp_subscription'     => ''
        );
    }

    public function setMerchantSerialNumber($value)
    {
        return $this->setParameter('merchantSerialNumber', $value);
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('base_url', $value);
    }

    public function setClientId($value)
    {
        return $this->setParameter('client_id', $value);
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('client_secret', $value);
    }

    public function setOcpSubscription($value)
    {
        return $this->setParameter('ocp_subscription', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CreditCardRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CreditCardRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\TransactionReferenceRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\TransactionReferenceRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CompletePurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\TransactionReferenceRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\TransactionReferenceRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CreditCardRequest', $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CardReferenceRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\CoreTrekStein\VippsOmnipay\Message\CardReferenceRequest', $parameters);
    }
}
