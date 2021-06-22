<?php

namespace Pindena\Omnipay\Vipps\Tests\Message;

use Http\Mock\Client;
use Pindena\Omnipay\Vipps\Gateway;
use Pindena\Omnipay\Vipps\Message\Response;
use Pindena\Omnipay\Vipps\Tests\GatewayTest;
use Pindena\Omnipay\Vipps\Message\CreditCardRequest;
use Pindena\Omnipay\Vipps\Message\TransactionReferenceRequest;

class CreditCardRequestTest extends GatewayTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new CreditCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => '10.00',
            'currency' => 'NOK',
            'card' => $this->getValidCard(),
            'description' => 'Dette er en transaksjon.',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['amount']);
        $this->assertSame('Dette er en transaksjon.', $data['transactionText']);
    }

    public function testCreditCardSuccess()
    {
        $response = $this->gateway->authorize([
            'amount' => 10,
            'card' => $this->getValidCard(),
        ])->send();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }
}
