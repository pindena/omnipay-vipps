<?php

namespace Pindena\Omnipay\Vipps\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Pindena\Omnipay\Vipps\Message\Response\DetailsResponse;

/**
 * Details request
 *
 * This request fetches payment details from Vipps.
 */
class DetailsRequest extends Request
{
    public function getData()
    {
        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $orderId = $this->getTransactionReference();

        $response = $this->getRequest("/ecomm/v2/payments/{$orderId}/details", [
            'Authorization' => $this->getAccessToken(),
        ]);

        return $this->response = new DetailsResponse($this, $response);
    }
}
