<?php

namespace Pindena\Omnipay\Vipps\Tests;

use Omnipay\Tests\GatewayTestCase;
use Pindena\Omnipay\Vipps\Gateway;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;
use Pindena\Omnipay\Vipps\Message\Request\AuthorizeRequest;
use Pindena\Omnipay\Vipps\Message\Response\AuthorizeResponse;

class GatewayTest extends GatewayTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function getValidPhone()
    {
        return [
            'number' => '+4791236172',
        ];
    }

    public function testAuthorize()
    {
        $options = [
            'amount' => '10.00',
            'card' => $this->getValidPhone(),
        ];
        $request = $this->gateway->authorize($options);

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertArrayHasKey('amount', $request->getData());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(['amount' => '10.00']);

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCapture()
    {
        $request = $this->gateway->capture(['amount' => '10.00']);

        $this->assertInstanceOf(CaptureRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
