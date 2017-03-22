<?php
namespace Oktey\Api\Test;

use PHPUnit\Framework\TestCase;

use Oktey\Api\Client;

class ClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Client = new Client('abcdef', 'ghijkl');
    }

    public function tearDown()
    {
        $this->Client = null;
        parent::tearDown();
    }

    public function testUrl()
    {
        $url = $this->Client->url('/customers');
        $this->assertSame('https://api.oktey.com/v1/customers', $url);

        $url = $this->Client->url('order/create');
        $this->assertSame('https://api.oktey.com/v1/order/create', $url);
    }

    public function testTestMode()
    {
        // is false
        $this->assertFalse($this->Client->testMode());

        // set true
        $this->Client->testMode(true);
        $this->assertTrue($this->Client->testMode());

        // test url
        $url = $this->Client->url('/customers');
        $this->assertSame('https://api.oktey.com/v1-dev/customers', $url);
    }

    public function testDebugMode()
    {
        // is false
        $this->assertFalse($this->Client->debug());

        // set true
        $this->Client->debug(true);
        $this->assertTrue($this->Client->debug());
    }

}
