<?php

namespace Pindena\Omnipay\Vipps\Tests\Message;

use Pindena\Omnipay\Vipps\Tests\ResponseTestCase;
use Pindena\Omnipay\Vipps\Message\Response\Response;

class AuthorizeResponseTest extends ResponseTestCase
{
    public function testAuthorizeSuccess()
    {
        $response = new Response(
            $this->getMockRequest(),
            $this->getMockResponse('AuthorizeSuccess.txt', ['orderId' => 'abc123'])
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getTransactionReference());
    }
}