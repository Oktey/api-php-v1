<?php
namespace Oktey\Api\Test;

use PHPUnit\Framework\TestCase;

use Oktey\Api\Client;
use Oktey\Api\Request;
use Oktey\Api\Response;
use GuzzleHttp\Psr7\Response as GuzResponse;

class ResponseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $args = [];
        Client::debug(false);
        $this->Request = new Request('POST', '/customers', $args, 'abcdef', 'ghijkl');

        $this->_responseWithBody();
    }

    public function tearDown()
    {
        $this->Request = null;
        $this->Response = null;
        $this->guzzleResponse = null;
        parent::tearDown();
    }

    private function _responseWithBody($code = 200, $body = '')
    {
        $this->guzzleResponse = new GuzResponse($code, [], $body);
        $this->Response = new Response($this->Request, $this->guzzleResponse);
    }

    public function testStatusAndSuccess()
    {
        // default
        $this->assertSame(200, $this->Response->getStatus());
        $this->assertTrue($this->Response->success());

        // 410
        $guzzleResponse = new GuzResponse(410);
        $this->Response = new Response($this->Request, $guzzleResponse);
        $this->assertSame(410, $this->Response->getStatus());
        $this->assertFalse($this->Response->success());

        // 500
        $guzzleResponse = new GuzResponse(500);
        $this->Response = new Response($this->Request, $guzzleResponse);
        $this->assertSame(500, $this->Response->getStatus());
        $this->assertFalse($this->Response->success());
    }

    public function testBody()
    {
        $body = json_encode([
            'count' => 1,
        ]);
        $this->_responseWithBody(200, $body);

        $array = $this->Response->getBody();
        $this->assertInternalType('array', $array);
        $this->assertArrayHasKey('count', $array);

        Client::debug(true);
        $this->_responseWithBody(200, $body);
        $string = $this->Response->getBody();
        $this->assertInternalType('string', $string);

        $this->assertJsonStringEqualsJsonString($string, json_encode($array));
    }

    public function testCount()
    {
        $body = json_encode([
            'count' => 23,
        ]);
        $this->_responseWithBody(200, $body);
        $this->assertSame(23, $this->Response->getCount());
    }

    public function testData()
    {
        $body = json_encode([
            'count' => 1,
            'data' => [
                ['id' => 1, 'title' => 'customer 1'],
                ['id' => 3, 'title' => 'customer 3'],
            ],
        ]);
        $this->_responseWithBody(200, $body);
        $data = $this->Response->getData();
        $this->assertInternalType('array', $data);
        $this->assertCount(2, $data);
        $this->assertSame(3, $data[1]['id']);
    }

    public function testMessageError()
    {
        $body = json_encode([
            'error_message' => 'this is an error message for: field',
        ]);
        $this->_responseWithBody(200, $body);
        $this->assertContains(' for:', $this->Response->getMessageError());
    }
}
