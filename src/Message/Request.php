<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;

abstract class Request extends AbstractRequest
{
    public $successfulStatusCodes = [200, 201,];

    /**
     * @var string
     */
    public $liveBaseUrl = 'https://api.vipps.no';

    /**
     * @var string
     */
    public $testBaseUrl = 'https://apitest.vipps.no';

    public function getDefaultParameters()
    {
        return [
            'accessToken'          => '',
            'clientId'            => '',
            'clientSecret'        => '',
            'merchantSerialNumber' => '',
            'ocpSubscription'     => '',
            'serverUrl'           => '',
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

    public function getBaseUrl($path = null)
    {
        $endpoint = $this->getTestMode() ? $this->testBaseUrl : $this->liveBaseUrl;

        if (empty($path)) {
            return $endpoint;
        }

        return $endpoint . $path;
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

    public function setTransactionText($value)
    {
        return $this->setParameter('transactionText', $value);
    }

    public function getTransactionText()
    {
        return $this->getParameter('transactionText');
    }

    /**
     * Convenience method to make a POST request
     * Body will be json_encoded
     *
     * @param $uri
     * @param $headers
     * @param $body
     * @return array|mixed
     * @throws InvalidRequestException
     */
    public function postRequest($uri, $headers, $body = null)
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        return $this->makeVippsRequest('POST', $uri, $headers, $body);
    }

    /**
     * Convenience method to make a GET request
     *
     * @param $uri
     * @param $headers
     * @param $body
     * @return array|mixed
     * @throws InvalidRequestException
     */
    public function getRequest($uri, $headers, $body = null)
    {
        return $this->makeVippsRequest('GET', $uri, $headers, $body);
    }

    /**
     * Wrapping omnipay\common ClientInterface to make Vipps request with a default set of overridable headers
     *
     * @param $method
     * @param $uri
     * @param $headers
     * @param $body
     * @return array|mixed
     * @throws InvalidRequestException
     */
    public function makeVippsRequest($method, $uri, $headers, $body)
    {
        $headers = array_merge([
            'Content-Type' => 'application/json',
            'Vipps-System-Plugin-Version' => '1.0',
            'Vipps-System-Plugin-Name' => 'pindena/omnipay-vipps',
            'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription(),
            'Merchant-Serial-Number' => $this->getMerchantSerialNumber(),
        ], $headers);

        $uri = $this->getBaseUrl($uri);

        $response = $this->httpClient->request($method, $uri, $headers, $body);

        if (! in_array($response->getStatusCode(), $this->successfulStatusCodes)) {
            throw new InvalidRequestException($response->getBody()->getContents());
        }

        $body = $response->getBody()->getContents();

        return empty($body)
            ?  []
            : json_decode($body, true);
    }

    /**
     * Get a Vipps access token
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getAccessToken()
    {
        if (empty($this->getToken())) {
            $response = $this->postRequest("/accesstoken/get", [
                'Accept' => 'application/json',
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription(),
            ]);

            $accessToken = isset($response['access_token'])
                ? $response['access_token']
                : null;

            $this->setToken($accessToken);
        }

        return $this->getToken();
    }

    abstract public function getData();

    abstract public function sendData($data): \Omnipay\Common\Message\ResponseInterface;
}