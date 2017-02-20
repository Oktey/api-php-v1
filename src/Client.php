<?php

namespace Oktey\Api;

class Client
{
    const WRAPPER_VERSION = 'v1.0.0';

    static public $debug = false;

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
     * Api version
     * @var int
     */
    private $version = 1;

    /**
     * Guzzle Client
     * @var Guzzle
     */
    protected $Http = null;


    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    private function _getApiUrl()
    {
        return 'https://api.oktey.com/v' . $this->version ;
    }

    public function url($url)
    {
        if (strpos($url, '/') !== 0) {
            $url = '/' . $url;
        }

        return $this->_getApiUrl() . $url;

    }

    private function _call($method, $url, array $args = [])
    {
        return (new \Oktey\Api\Request($method, $url, $args, $this->key, $this->secret))->call(true);
    }

    public function get($url, array $args = [])
    {
        $response = $this->_call('POST', $this->url($url), $args);

        return $response;
    }

    /**
     * call API with post data
     * @param  string $url  short api url
     * @param  array  $args POST data
     * @return Response
     */
    public function post($url, array $args = [])
    {
        $response = $this->_call('POST', $this->url($url), $args);

        return $response;
    }

    public function debug($debug = null)
    {
        if ($debug === null) {
            return self::$debug;
        }
        self::$debug = $debug;
    }

}
