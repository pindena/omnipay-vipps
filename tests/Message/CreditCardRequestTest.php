<?php

namespace CoreTrekStein\VippsOmnipay\Message;

use CoreTrekStein\VippsOmnipay\Gateway;
use Omnipay\Tests\TestCase;

class CreditCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->request = new TransactionReferenceRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $request = new CreditCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'amount' => 10,
            'card' => $this->getValidCard(),
        ));
        $data = $request->getData();
        $this->assertSame(10, $data['amount']);
    }

    public function testCreditCardSuccess()
    {
        // card numbers ending in even number should be successful
        $options = array(
            'amount' => 10,
            'card' => $this->getValidCard(),
        );
        $options['card']['number'] = '91236172';
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf('\CoreTrekStein\VippsOmnipay\Message\Response', $response);
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
