<?php

namespace Pindena\Omnipay\Vipps\Tests\Message\Request;

use Pindena\Omnipay\Vipps\Tests\TestCase;
use Pindena\Omnipay\Vipps\Message\Request\CancelRequest;

class CancelRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new CancelRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame([], $data);
    }
}
