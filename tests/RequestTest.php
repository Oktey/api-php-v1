<?php

namespace Oktey\Api\Test;

use PHPUnit\Framework\TestCase;

use Oktey\Api\Request;

class RequestTest extends TestCase
{
    private $args = [
        'contact_name'  => "Erwane Breton",
        'contact_email' => "noreply@oktey.com",
        'has_mailin' => true,
        'has_mailout' => false,
    ];

    private $key = 'abcdef';
    private $secret = 'ghijkl';

    public function setUp()
    {
        parent::setUp();
    }

    public function testTimestamp()
    {
        $this->Request = new Request('POST', '/customers/lite', $this->args, $this->key, $this->secret);

        // Call at same time for same date
        $DateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $call = $this->Request->call(false);

        $this->assertEquals($this->Request->args['timestamp'], $DateTime->format('c'));
    }

    public function testUniqId()
    {
        $request1 = new Request('POST', '/customers/lite', $this->args, $this->key, $this->secret);
        $request2 = new Request('POST', '/customers/lite', $this->args, $this->key, $this->secret);

        $call1 = $request1->call(false);
        $call2 = $request2->call(false);

        $this->assertNotEquals($call1->request->args['uniqid'], $call2->request->args['uniqid']);
    }

    public function testSigningSuccess()
    {
        $this->Request = new Request('POST', '/customers/lite', $this->args, $this->key, $this->secret);
        $call = $this->Request->call(false);

        $this->assertEquals($this->checkHmac($this->Request, $this->secret), true);
    }

    public function testSigningFailed()
    {
        $this->Request = new Request('POST', '/customers/lite', $this->args, $this->key, $this->secret);
        $call = $this->Request->call(false);

        $this->assertEquals($this->checkHmac($this->Request, $this->secret . 'a'), false);
        $this->assertEquals($this->checkHmac($this->Request, 'a' . $this->secret), false);
        $this->assertEquals($this->checkHmac($this->Request, $this->secret + 1), false);
        $this->assertEquals($this->checkHmac($this->Request, $this->secret . 1), false);
    }

    private function checkHmac(Request $Request, $secret)
    {
        $args = [];
        foreach($Request->args as $k => $v) {
            if ($k === 'hmac') {
                continue;
            }

            if (is_bool($v)) {
                $v = $v ? 1 : 0;
            }

            $args[] = $k . '=' . $v;
        }

        return strtoupper(hash('sha512', implode('&',$args) . $secret)) === $Request->args['hmac'];
    }

}
