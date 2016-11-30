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


    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    private function getApiUrl()
    {
        return 'http://192.168.33.52/v' . $this->version ;
    }

    private function _call($method, $url, array $args = [])
    {
        return (new \Oktey\Api\Request($method, $url, $args, $this->key, $this->secret))->call(true);
    }

    public function get(string $url, array $args = [])
    {
        $response = $this->_call('POST', $this->getApiUrl() . $url, $args);

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
