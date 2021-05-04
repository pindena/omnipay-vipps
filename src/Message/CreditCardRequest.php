<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay Authorize/Purchase Request
 *
 * This is the request that will be called for any transaction which submits a credit card,
 * including `authorize` and `purchase`
 */
class CreditCardRequest extends AbstractRequest
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
    
    public function getData()
    {
        $access_token = $this->getAccessToken();
        $order_id = $this->getRandomOrderID();

        $transactionText = 'Pindena transaksjonstekst';
        $phone = $this->getCard()->getNumber();
        $amount = intval($this->getAmount());

        $payment = $this->createPayment($order_id, $access_token, $amount, $transactionText, $phone);

        $data = array();
        $data['amount'] = $amount;
        $data['order_id'] = $order_id;
        $data['access_token'] = $access_token;
        $data['phone'] = $phone;
        $data['url'] = $payment['url'] ?? '';

        return $data;
    }

    public function sendData($data)
    {        
        $data['reference'] = uniqid();
        $data['success'] = 0 === substr($this->getCard()->getNumber(), -1, 1) % 2;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }

    public function getAccessToken() 
    {
        $httpResponse = $this->httpClient->request('POST',
            $this->getParameter('base_url') . '/accesstoken/get',
            array(
                'Accept' => 'application/json',
                'client_id' => $this->getParameter('client_id'),
                'client_secret' => $this->getParameter('client_secret'),
                'Ocp-Apim-Subscription-Key' => $this->getParameter('ocp_subscription')
            ),
            ''
        );

        $body = (string) $httpResponse->getBody()->getContents();

        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
    
        return $jsonToArrayResponse['access_token'] ?? 'access-token-123';
    }

    public function getRandomOrderID($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createPayment($orderID, $access_token, $transaction_amount, $transaction_text, $customer_number) 
    {
        $httpResponse = $this->httpClient->request('POST',
            $this->getParameter('base_url') . '/ecomm/v2/payments',
            array(
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $this->getParameter('ocp_subscription'),
                'Authorization' => $access_token
            ),
            '{
                "customerInfo": {
                    "mobileNumber": "' . $customer_number . '"
                }, 
                "merchantInfo": {
                    "consentRemovalPrefix": "' . $this->getServerUrl() . '/vipps", 
                    "callbackPrefix": "' . $this->getServerUrl() . '?action=capture&order_id=' . $orderID . '&access_token=' . $access_token . '", 
                    "shippingDetailsPrefix": "' . $this->getServerUrl() . '/gateways/VippsOmnipay/authorize?a=shipping", 
                    "fallBack": "' . $this->getServerUrl() . '?action=checkPayment&order_id=' . $orderID . '", 
                    "isApp": false, 
                    "merchantSerialNumber": "' . $this->getParameter('merchantSerialNumber') . '", 
                    "paymentType": "eComm Regular Payment"
                }, 
                "transaction": {
                    "amount": "' . $transaction_amount . '", 
                    "orderId": "' . $orderID . '", 
                    "timeStamp": "' . date('c') . '", 
                    "transactionText": "' . $transaction_text . '"
                }
            }'
        );

        $body = (string) $httpResponse->getBody()->getContents();

        return $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
    }
}