<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\Response;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;

class CaptureRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => '10.00',
            'description' => 'This is a transaction.',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('This is a transaction.', $data['transaction']['transactionText']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'CaptureSuccess.txt']);
        $response = $this->request->send();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'CaptureFailure.txt']);
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Requested Order not found', $response->getMessage());
    }
}
