<?php

namespace Pindena\Omnipay\Vipps\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * VippsOmnipay UpdateCard/DeleteCard Request
 *
 * This is the request that will be called for any transaction which submits a cardReference.
 */
class CardReferenceRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('cardReference');
        return array('cardReference' => $this->getCardReference());
    }

    public function sendData($data)
    {
        $data['reference'] = $this->getCardReference();
        $data['success'] = 0 === substr($this->getCardReference(), -1, 1) % 2;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }

    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
    }
}
