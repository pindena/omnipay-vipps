<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Response;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\AuthorizeResponse;

class AuthorizeResponseTest extends TestCase
{
    public function testAuthorizeSuccess()
    {
        $response = new AuthorizeResponse(
            $this->getMockRequest(),
            $this->getMockResponse('AuthorizeSuccess.txt', ['orderId' => 'abc123'])
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionReference());

        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://api.vipps.no/dwo-api-application/v1/deeplink/vippsgateway?v=2&token=eyJraWQiOiJqd3RrZXkiLC <snip>', $response->getRedirectUrl());
        $this->assertSame(null, $response->getRedirectData());
    }
}
