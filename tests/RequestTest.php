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
        $args = [
            'contact_name'  => "Erwane Breton",
            'contact_email' => "noreply@oktey.com",
            'has_mailin' => true,
            'has_mailout' => false,
            'mailin' => [
                'dest' => [
                    'servers' => ['e02.amailor.com'],
                ],
            ],
        ];

        $this->Request = new Request('POST', '/customers', $args, $this->key, $this->secret);
        $signed = $this->Request->signRequest($args);

        $this->assertEquals($this->checkHmac($signed, $this->secret), true);
    }

    public function testSigningFailed()
    {
        $args = [
            'contact_name'  => "Erwane Breton",
            'contact_email' => "noreply@oktey.com",
            'has_mailin' => true,
            'has_mailout' => false,
        ];
        $this->Request = new Request('POST', '/customers', $args, $this->key, $this->secret);
        $signed = $this->Request->signRequest($args);

        $this->assertEquals($this->checkHmac($signed, $this->secret . 'a'), false);
        $this->assertEquals($this->checkHmac($signed, 'a' . $this->secret), false);
        $this->assertEquals($this->checkHmac($signed, $this->secret . 1), false);
    }

    private function checkHmac($signed, $secret)
    {
        $hmac = $signed['hmac'];
        unset($signed['hmac']);

        return strtoupper(hash('sha512', json_encode($signed) . $secret)) === $hmac;
    }

}
