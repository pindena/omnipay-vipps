<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Request\CaptureRequest;

class CaptureRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => '10.00',
            'description' => 'This is a transaction.',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('This is a transaction.', $data['transaction']['transactionText']);
    }
}
