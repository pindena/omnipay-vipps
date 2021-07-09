<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Response;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Response\DetailsResponse;

class DetailsResponseTest extends TestCase
{
    public function testDetailsSuccess()
    {
        $response = new DetailsResponse(
            $this->getMockRequest(),
            $this->getMockResponse('DetailsSuccess.txt')
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2161081625816819478', $response->getTransactionReference());
    }
}
