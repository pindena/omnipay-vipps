<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class TransactionReferenceRequest extends Request
{
    public function getOrderAmount($orderId, $token)
    {
        $response = $this->getRequest("/ecomm/v2/payments/{$orderId}/details", [
            'Authorization' => $token,
            'X-Request-Id' => uniqid('', true),
        ]);

        return intval($response['transactionLogHistory'][0]['amount']);
    }

    public function getData()
    {
        $token = $_GET['access_token'];
        $transactionText = $this->getDescription();
        $token = explode('/v2/', $token)[0];
        $orderId = $this->getTransactionReference();
        $amount = $this->getOrderAmount($orderId, $token);

        $response = $this->postRequest("/ecomm/v2/payments/{$orderId}/capture", [
            'Authorization' => $token,
            'X-Request-Id' => uniqid('', true),
        ], [
            'merchantInfo' => [
                'merchantSerialNumber' => $this->getMerchantSerialNumber(),
            ],
            'transaction' => [
                'amount' => $amount,
                'transactionText' => $transactionText,
            ]
        ]);
 
        return ['transactionReference' => $this->getTransactionReference()];
    }

    public function sendData($data): ResponseInterface
    {        
        $data['reference'] = $this->getTransactionReference();
        $data['success'] = strpos($this->getTransactionReference(), 'fail') !== false ? false : true;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }
}
