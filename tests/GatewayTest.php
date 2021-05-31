<?php

namespace Pindena\Omnipay\Vipps\Tests;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;
use Pindena\Omnipay\Vipps\Gateway;
use Pindena\Omnipay\Vipps\Message\CreditCardRequest;
use Pindena\Omnipay\Vipps\Message\TransactionReferenceRequest;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Pindena\Omnipay\Vipps\Gateway
     */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    // Not implemented

    public function testUpdateCardParameters()
    {
        $this->assertSame('1', '1');
    }

    // Not implemented

    public function testDeleteCardParameters()
    {
        $this->assertSame('1', '1');
    }

    // testAuthorize

    public function testAuthorize()
    {
        $options = array(
            'amount' => '10.00',
            'card' => new CreditCard(array(
                'number' => '91236172',
            ))
        );
        $request= $this->gateway->authorize($options);

        $this->assertInstanceOf(CreditCardRequest::class, $request);
        $this->assertArrayHasKey('amount', $request->getData());
    }

    // testPurchase

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));
        $this->assertInstanceOf(CreditCardRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    // testApprove

    public function testCapture()
    {
        $request = $this->gateway->capture(array('amount' => '10.00'));

        $this->assertInstanceOf(TransactionReferenceRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}