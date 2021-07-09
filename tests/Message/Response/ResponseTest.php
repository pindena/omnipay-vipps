<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Response;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class ResponseTest extends TestCase
{
    public function testCaptureResponse()
    {
        $response = new Response(
            $this->getMockRequest(),
            $this->getMockResponse('CaptureSuccess.txt', ['orderId' => 'abc123'])
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionId());
        $this->assertSame('abc123', $response->getTransactionReference());
    }

    public function testFailure()
    {
        $response = new Response(
            $this->getMockRequest(),
            $this->getMockResponse('FailureResponse.txt')
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }
}
