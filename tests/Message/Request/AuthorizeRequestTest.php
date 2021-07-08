<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

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
            'phone' => $this->getValidPhone(),
            'description' => 'This is a transaction.',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('+4799999999', $data['customerInfo']['mobileNumber']);
        $this->assertSame('This is a transaction.', $data['transaction']['transactionText']);
    }
}
