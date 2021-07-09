<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\Response;
use Pindena\Omnipay\Vipps\Message\Request\CancelRequest;

class CancelRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new CancelRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame([], $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'CancelSuccess.txt']);
        $response = $this->request->send();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isCancelled());
        $this->assertFalse($response->isRedirect());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'CancelFailure.txt']);
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Can\'t cancel already captured order', $response->getMessage());
    }
}
