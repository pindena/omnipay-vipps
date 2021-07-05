<?php

namespace Pindena\Omnipay\Vipps\Tests\Message;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Request\AuthorizeRequest;

class AuthorizeRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
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

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('Dette er en transaksjon.', $data['transaction']['transactionText']);
    }
}
