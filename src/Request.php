<?php

namespace Oktey\Api;

class Request extends \GuzzleHttp\Client
{
    /**
     * Build a new Http request
     * @param string $method  http method
     * @param string $url     call url
     * @param array  $args    Request args
     */
    public function __construct($method, $url, $args)
    {
        parent::__construct(['defaults' => [
            'headers' => [
                'user-agent' => 'OkteyApi/' . \Oktey\Api\Client::WRAPPER_VERSION
            ]
        ]]);
        $this->method = $method;
        $this->url = $url;
        $this->args = $args;
    }

    public function call() {
        $payload = [
            'headers'  => ['content-type' => 'text/json'],
            'json' => $this->args,
        ];

        try {
            $response = call_user_func_array(
                array($this, strtolower($this->method)), [
                $this->url, $payload]
            );
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        }

        return new \Oktey\Api\Response($this, $response);
    }
}
