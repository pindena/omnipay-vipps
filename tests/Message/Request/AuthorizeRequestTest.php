<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Request\AuthorizeRequest;
use Pindena\Omnipay\Vipps\Message\Response\AuthorizeResponse;

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
            'headers' => [
                'X-Extra-Header' => 'Testing',
            ],
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('+4799999999', $data['customerInfo']['mobileNumber']);
        $this->assertSame('This is a transaction.', $data['transaction']['transactionText']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'AuthorizeSuccess.txt']);
        $response = $this->request->send();

        $this->assertInstanceOf(AuthorizeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame(null, $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse(['AccessToken.txt', 'AuthorizeFailure.txt']);
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('orderId is already used, and must be unique', $response->getMessage());
    }
}
