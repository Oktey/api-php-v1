<?php

namespace Oktey\Api;

class Request extends \GuzzleHttp\Client
{
    /**
     * Api key
     * @var string or null
     */
    private $key = null;

    /**
     * Api secret
     * @var string or null
     */
    private $secret = null;

    /**
     * Build a new Http request
     * @param string $method  http method
     * @param string $url     call url
     * @param array  $args    Request args
     */
    public function __construct($method, $url, $args, $key, $secret)
    {
        parent::__construct(['defaults' => [
            'headers' => [
                'user-agent' => 'OkteyApi/' . \Oktey\Api\Client::WRAPPER_VERSION
            ]
        ]]);
        $this->method = $method;
        $this->url = $url;
        $this->args = $args;
        $this->key = $key;
        $this->secret = $secret;
    }

    public function call($call = true) {

        $this->signRequest();

        $payload = [
            'form_params' => $this->args,
        ];

        $response = null;
        if ($call) {
            try {
                $response = call_user_func_array(
                    array($this, strtolower($this->method)), [
                    $this->url, $payload]
                );
            }
            catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
            }
            catch (\GuzzleHttp\Exception\ServerException $e) {
                $response = $e->getResponse();
            }
        }

        return new \Oktey\Api\Response($this, $response);
    }

    private function signRequest()
    {

        // date time in UTC format
        $DateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->args['timestamp'] = $DateTime->format('c');

        // Uniq id
        $this->args['uniqid'] = hash('sha256', openssl_random_pseudo_bytes(64, $strongSource));

        // Url
        $this->args['url'] = $this->url;

        // key
        $this->args['key'] = $this->key;

        $args = [];
        foreach($this->args as $k => $v) {
            $args[] = $k . '=' . $v;
        }

        $this->args['hmac'] = strtoupper(hash('sha512', implode('&',$args) . $this->secret));
    }

}
