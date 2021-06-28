<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\Response;
use Pindena\Omnipay\Vipps\Message\Response\AuthorizeResponse;

/**
 * VippsOmnipay Authorize/Purchase Request
 *
 * This is the request that will be called for any transaction which submits a credit card,
 * including `authorize` and `purchase`
 */
class AuthorizeRequest extends Request
{
    protected $zeroAmountAllowed = false;

    public function getData()
    {
        $data = [];

        $data['fallBack'] = $this->getReturnUrl();
        $data['orderId'] = $this->getTransactionId();
        $data['phone'] = $this->getCard()->getNumber();
        $data['accessToken'] = $this->getAccessToken();
        $data['callbackPrefix'] = $this->getNotifyUrl();
        $data['transactionText'] = $this->getDescription();
        $data['amount'] = intval($this->getAmount() * 100);
        $data['merchantSerialNumber'] = $this->getMerchantSerialNumber();

        return $data;
    }

    public function sendData($data): ResponseInterface
    {
        list(
            'phone' => $phone,
            'amount' => $amount,
            'orderId' => $orderId,
            'fallBack' => $fallBack,
            'accessToken' => $accessToken,
            'callbackPrefix' => $callbackPrefix,
            'transactionText' => $transactionText,
            'merchantSerialNumber' => $merchantSerialNumber
        ) = $data;

        $response = $this->postRequest("/ecomm/v2/payments", [
            'Authorization' => $accessToken,
        ], [
            'customerInfo' => [
                'mobileNumber' => $phone,
            ],
            'merchantInfo' => [
                'isApp' => false,
                'fallBack' => $fallBack,
                'callbackPrefix' => $callbackPrefix,
                'paymentType' => 'eComm Regular Payment',
                'merchantSerialNumber' => $merchantSerialNumber,
                'consentRemovalPrefix' => "{$this->getServerUrl()}/vipps",
                'shippingDetailsPrefix' => "{$this->getServerUrl()}/gateways/VippsOmnipay/authorize?a=shipping",
            ],
            'transaction' => [
                'amount' => $amount,
                'orderId' => $orderId,
                'timeStamp' => date('c'),
                'transactionText' => $transactionText,
            ],
        ]);

        return $this->response = new AuthorizeResponse($this, $response);
    }
}