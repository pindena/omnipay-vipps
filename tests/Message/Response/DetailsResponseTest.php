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

    public function testDetailsReserved()
    {
        $response = new DetailsResponse(
            $this->getMockRequest(),
            $this->getMockResponse('DetailsReserved.txt')
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isReserved());
    }

    public function testDetailsCancelled()
    {
        $response = new DetailsResponse(
            $this->getMockRequest(),
            $this->getMockResponse('DetailsCancelled.txt')
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isCancelled());
    }
}
