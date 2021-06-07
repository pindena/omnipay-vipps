<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay Complete/Capture/Void/Refund Request
 *
 * This is the request that will be called for any transaction which submits a transactionReference.
 */
class CompletePurchaseRequest extends Request
{
    public function getData()
    {
        $orderId = $this->getTransactionReference();

        $response = $this->getRequest("/ecomm/v2/payments/{$orderId}/details", [
            'Authorization' => $this->getAccessToken(),
            'X-Request-Id' => uniqid('', true),
        ]);

        $operation = $response['transactionLogHistory'][0]['operation'];
        $operationSuccess = $response['transactionLogHistory'][0]['operationSuccess'];

        if ($operation == 'CAPTURE' && $operationSuccess) {
            $operationSuccess = true;
        } else {
            $operationSuccess = false;
        }
 
        return ['success' => $operationSuccess];
    }

    public function sendData($data): ResponseInterface
    {        
        $data['reference'] = $this->getTransactionReference();
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }
}
