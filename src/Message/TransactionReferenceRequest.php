<?php
namespace CoreTrekStein\VippsOmnipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class TransactionReferenceRequest extends AbstractRequest
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

    public function getServerUrl()
    {
        return $this->getParameter('server_url');
    }

    public function getClientId()
    {
        return $this->getParameter('client_id');
    }

    public function getClientSecret()
    {
        return $this->getParameter('client_secret');
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('base_url', $value);
    }

    public function setServerUrl($value)
    {
        return $this->setParameter('server_url', $value);
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

    public function getOrderAmount($orderID, $token)
    {
        $httpResponse = $this->httpClient->request('GET',
            $this->getParameter('base_url') . '/ecomm/v2/payments/' . $orderID . '/details',
            array(
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $this->getParameter('ocp_subscription'),
                'Authorization' => $token,
                'X-Request-Id' => uniqid('', true)
            ),
            ''
        ); 

        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();

        return intval($jsonToArrayResponse['transactionLogHistory'][0]['amount']);
    }

    public function getData()
    {
        $orderID = $_GET['order_id'];
        $token = $_GET['access_token'];
        $transactionText = "Pindena transaksjonstekst";
        $token = explode('/v2/', $token)[0];
        $amount = $this->getOrderAmount($orderID, $token);

        $httpResponse = $this->httpClient->request('POST',
            $this->getParameter('base_url') . '/ecomm/v2/payments/' . $orderID . '/capture',
            array(
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $this->getParameter('ocp_subscription'),
                'Authorization' => $token,
                'X-Request-Id' => uniqid('', true)
            ),
            '{
                "merchantInfo": {
                    "merchantSerialNumber": "' . $this->getParameter('merchantSerialNumber') . '"
                }, 
                "transaction": {
                    "amount": "' . $amount . '",
                    "transactionText": "' . $transactionText . '"
                }
            }'
        );

        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
 
        return array('transactionReference' => $this->getTransactionReference());
    }

    public function sendData($data)
    {        
        $data['reference'] = $this->getTransactionReference();
        $data['success'] = strpos($this->getTransactionReference(), 'fail') !== false ? false : true;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }
}
