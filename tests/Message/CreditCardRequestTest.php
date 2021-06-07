<?php

namespace Pindena\Omnipay\Vipps\Tests\Message;

use Http\Mock\Client;
use Pindena\Omnipay\Vipps\Gateway;
use Pindena\Omnipay\Vipps\Message\Response;
use Pindena\Omnipay\Vipps\Tests\GatewayTest;
use Pindena\Omnipay\Vipps\Message\CreditCardRequest;
use Pindena\Omnipay\Vipps\Message\TransactionReferenceRequest;

class CreditCardRequestTest extends GatewayTest
{
    public function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->request = new TransactionReferenceRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $request = new CreditCardRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize([
            'amount' => 10,
            'card' => $this->getValidCard(),
            'description' => 'Dette er en transaksjon.',
        ]);

        $data = $request->getData();

        $this->assertSame(1000, $data['amount']);
        $this->assertSame('Dette er en transaksjon.', $data['transactionText']);
    }

    public function testCreditCardSuccess()
    {
        // card numbers ending in even number should be successful
        $options = [
            'amount' => 10,
            'card' => $this->getValidCard(),
        ];
        $options['card']['number'] = '91236172';
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    /*public function testCapture()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $response = $this->request->send();
    }*/
}
