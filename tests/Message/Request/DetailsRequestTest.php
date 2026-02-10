<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\DetailsResponse;
use Pindena\Omnipay\Vipps\Message\Request\DetailsRequest;

class DetailsRequestTest extends TestCase
{
    protected $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new DetailsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame([], $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'DetailsSuccess.txt']);
        $response = $this->request->send();

        $this->assertInstanceOf(DetailsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'DetailsFailure.txt']);
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Requested Order not found', $response->getMessage());
    }
}
