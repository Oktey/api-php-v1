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
     * @param string  $key    reseller key
     * @param string  $secret reseller secret
     */
    public function __construct($method, $url, $args, $key, $secret)
    {
        parent::__construct([
            'defaults' => [
                'headers' => [
                    'user-agent' => 'OkteyApi/' . \Oktey\Api\Client::WRAPPER_VERSION
                ],
            ],
            'verify' => true,
            'connect_timeout' => 10,
        ]);
        $this->method = $method;
        $this->url = $url;
        $this->args = $args;
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * call Guzzle method
     * @param  bool $call really call the method (debug ?)
     * @return Oktey\Api\Response
     */
    public function call($call = true)
    {
        $this->args = $this->signRequest($this->args);

        $payload = [
            'form_params' => $this->args,
        ];

        $response = null;
        if ($call) {
            try {
                $response = call_user_func_array(
                    [$this, strtolower($this->method)],
                    [$this->url, $payload]
                );
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $response = $e->getResponse();
            }
        }

        return new \Oktey\Api\Response($this, $response);
    }

    /**
     * add POST arguments for signing request
     * @return void
     */
    public function signRequest($args)
    {
        // date time in UTC format
        $DateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $args['timestamp'] = $DateTime->format('c');

        // Uniq id
        $args['uniqid'] = hash('sha256', openssl_random_pseudo_bytes(64, $strongSource));

        // Url
        $args['url'] = $this->url;

        // key
        $args['key'] = $this->key;

        // transtypage for json
        $args = $this->_argsToJson($args);

        $args['hmac'] = strtoupper(hash('sha512', json_encode($args) . $this->secret));

        return $args;
    }

    /**
     * argToJson convert the arguments array to nice formated value array
     * @param  array $ary arguments
     * @return array formated
     */
    private function _argsToJson($ary)
    {
        foreach ($ary as $k => &$v) {
            if (is_array($v)) {
                $v = $this->_argsToJson($v);
            } elseif (is_bool($v)) {
                $v = $v ? '1' : '0'; // To string
            } elseif (is_numeric($v)) {
                $v = (string)$v; // To string
            }
        }

        return $ary;
    }
}
