<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getDefaultParameters()
    {
        return array(
            'base_url'             => '',
            'client_id'            => '',
            'client_secret'        => '',
            'merchantSerialNumber' => '',
            'ocp_subscription'     => '',
            'server_url'           => ''
        );
    }

    public function setMerchantSerialNumber($value)
    {
        return $this->setParameter('merchantSerialNumber', $value);
    }

    public function getMerchantSerialNumber()
    {
        return $this->getParameter('merchantSerialNumber');
    }

    public function getOcpSubscription()
    {
        return $this->getParameter('ocp_subscription');
    }

    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('base_url', $value);
    }

    public function getServerUrl()
    {
        return $this->getParameter('server_url');
    }

    public function setServerUrl($value)
    {
        return $this->setParameter('server_url', $value);
    }

    public function getClientId()
    {
        return $this->getParameter('client_id');
    }

    public function getClientSecret()
    {
        return $this->getParameter('client_secret');
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

    public function getData()
    {
        $orderID = $this->getTransactionReference();
        $httpResponse = $this->httpClient->request('GET',
            $this->getBaseUrl() . '/ecomm/v2/payments/' . $orderID . '/details',
            array(
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription(),
                'Authorization' => $this->getAccessToken(),
                'X-Request-Id' => uniqid('', true)
            ),
            ''
        ); 

        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();

        $operation = $jsonToArrayResponse['transactionLogHistory'][0]['operation'];
        $operationSuccess = $jsonToArrayResponse['transactionLogHistory'][0]['operationSuccess'];

        if ($operation == 'CAPTURE' && $operationSuccess) {
            $operationSuccess = true;
        } else {
            $operationSuccess = false;
        }
 
        return array('success' => $operationSuccess);
    }

    public function sendData($data)
    {        
        $data['reference'] = $this->getTransactionReference();
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }

    public function getAccessToken() 
    {
        $httpResponse = $this->httpClient->request('POST',
            $this->getBaseUrl() . '/accesstoken/get',
            array(
                'Accept' => 'application/json',
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription()
            ),
            ''
        );

        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
    
        return $jsonToArrayResponse['access_token'] ?? 'access-token-123';
    }
}
