<?php

namespace Pindena\Omnipay\Vipps\Tests;

use Omnipay\Tests\GatewayTestCase;
use Pindena\Omnipay\Vipps\Gateway;
use Pindena\Omnipay\Vipps\Message\Request\CancelRequest;
use Pindena\Omnipay\Vipps\Message\Request\RefundRequest;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;
use Pindena\Omnipay\Vipps\Message\Request\DetailsRequest;
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
        return '+4799999999';
    }

    public function testAuthorize()
    {
        $options = [
            'amount' => '10.00',
            'phone' => $this->getValidPhone(),
        ];
        $request = $this->gateway->authorize($options);

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertArrayHasKey('transaction', $request->getData());
        $this->assertArrayHasKey('amount', $request->getData()['transaction']);
    }

    public function testCompleteAuthorize()
    {
        $request = $this->gateway->completeAuthorize();

        $this->assertInstanceOf(DetailsRequest::class, $request);
    }

    public function testCapture()
    {
        $request = $this->gateway->capture(['amount' => '10.00']);

        $this->assertInstanceOf(CaptureRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(['amount' => '10.00']);

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase(['amount' => '10.00']);

        $this->assertInstanceOf(CaptureRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction();

        $this->assertInstanceOf(DetailsRequest::class, $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(['amount' => '10.00']);

        $this->assertInstanceOf(RefundRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testVoid()
    {
        $request = $this->gateway->void();

        $this->assertInstanceOf(CancelRequest::class, $request);
    }
}
