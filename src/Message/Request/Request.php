<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
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
            'clientId' => '',
            'clientSecret' => '',
            'ocpSubscription' => '',
            'merchantSerialNumber' => '',
            'accessToken' => '',
        ];
    }

    public function getBaseUrl($path = '')
    {
        $endpoint = $this->getTestMode() ? $this->testBaseUrl : $this->liveBaseUrl;

        return $endpoint . $path;
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

    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setHeaders($value)
    {
        return $this->setParameter('headers', $value);
    }

    /**
     * Build an array with all the http headers
     *
     * @param $headers
     * @return array $headers
     */
    public function getHeaders($headers = [])
    {
        $externalHeaders = is_array($this->getParameter('headers'))
            ? $this->getParameter('headers')
            : [];

        return array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Vipps-System-Plugin-Version' => '1.0',
            'Vipps-System-Plugin-Name' => 'pindena/omnipay-vipps',
            'Ocp-Apim-Subscription-Key' => $this->getOcpSubscription(),
            'Merchant-Serial-Number' => $this->getMerchantSerialNumber(),
            'X-Request-Id' => uniqid('', true),
        ], $externalHeaders, $headers);
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
    public function postRequest($uri, $headers = [], $body = null)
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
    public function getRequest($uri, $headers = [], $body = null)
    {
        return $this->makeVippsRequest('GET', $uri, $headers, $body);
    }

    /**
     * Convenience method to make a PUT request
     *
     * @param $uri
     * @param array $headers
     * @param null $body
     * @return array|mixed
     * @throws InvalidRequestException
     */
    public function putRequest($uri, $headers = [], $body = null)
    {
        return $this->makeVippsRequest('PUT', $uri, $headers, $body);
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
        $headers = $this->getHeaders($headers);

        $uri = $this->getBaseUrl($uri);

        $response = $this->httpClient->request($method, $uri, $headers, $body);

        $body = $response->getBody()->getContents();

        return empty($body) ? [] : json_decode($body, true);
    }

    /**
     * Get a Vipps access token
     *
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getAccessToken()
    {
        if (empty($this->getToken())) {
            $response = $this->postRequest("/accesstoken/get", [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
            ]);

            $accessToken = isset($response['access_token']) ? $response['access_token'] : null;

            $this->setToken($accessToken);
        }

        return $this->getToken();
    }

    /**
     * Get details for transaction
     *
     * @param $orderId
     * @return array
     */
    public function getDetails($orderId)
    {
        return $this->getRequest("/ecomm/v2/payments/{$orderId}/details", [
            'Authorization' => $this->getAccessToken(),
        ]);
    }

    abstract public function getData();

    abstract public function sendData($data): ResponseInterface;
}
