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
    public $testBaseUrl = 'https://apitest.vipps.no/';

    /**
     * @var string
     */
    public $liveBaseUrl = 'https://api.vipps.no/';

    public $version = 'v2';

    public function getDefaultParameters()
    {
        return [
            'accessToken'          => '',
            'base_url'             => '',
            'client_id'            => '',
            'client_secret'        => '',
            'merchantSerialNumber' => '',
            'ocp_subscription'     => '',
            'server_url'           => '',
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
        return $this->setParameter('ocp_subscription', $value);
    }

    public function getOcpSubscription()
    {
        return $this->getParameter('ocp_subscription');
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
        return $this->setParameter('server_url', $value);
    }

    public function getServerUrl()
    {
        return $this->getParameter('server_url');
    }

    public function setClientId($value)
    {
        return $this->setParameter('client_id', $value);
    }

    public function getClientId()
    {
        return $this->getParameter('client_id');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('client_secret', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('client_secret');
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
            'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription(),
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
            $response = $this->postRequest($this->getBaseUrl('/accesstoken/get'), [
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