<?php

namespace Pindena\Omnipay\Vipps\Tests\Message;

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

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getTransactionReference());
    }
}
