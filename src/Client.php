<?php
namespace Oktey\Api;

class Client
{
    /**
     * Api Version for agent
     */
    const WRAPPER_VERSION = 'v1.0.5';

    /**
     * enable or disable the response debug
     * @var bool
     */
    static public $debug = false;

    /**
     * test mode use a "-dev" suffixed url
     * @var bool
     */
    private $testMode = false;

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

    /**
     * constructore
     * @param string $key    reseller key
     * @param string $secret reseller secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * return the api base url
     * @return string
     */
    private function getApiUrl()
    {
        $url = 'https://api.oktey.com/v' . $this->version;

        if ($this->testMode) {
            $url .= '-dev';
        }

        return $url;
    }

    /**
     * build api url
     * @param  string $url requested api url
     * @return string
     */
    public function url($url)
    {
        if (strpos($url, '/') !== 0) {
            $url = '/' . $url;
        }

        return $this->getApiUrl() . $url;
    }

    /**
     * call the Api\Request::$method() with args
     * @param  string $method called method
     * @param  string $url    api url
     * @param  array  $args   arguments or data
     * @return Oktey\Api\Response
     */
    private function _call($method, $url, array $args = [])
    {
        return (new \Oktey\Api\Request($method, $url, $args, $this->key, $this->secret))->call(true);
    }

    /**
     * fake get method, only for style
     * @param  string $url  api url
     * @param  array  $args url args
     * @return Oktey\Api\Response
     */
    public function get($url, array $args = [])
    {
        $response = $this->_call('POST', $this->url($url), $args);

        return $response;
    }

    /**
     * call API with post data
     * @param  string $url  short api url
     * @param  array  $args POST data
     * @return Oktey\Api\Response
     */
    public function post($url, array $args = [])
    {
        $response = $this->_call('POST', $this->url($url), $args);

        return $response;
    }

    /**
     * api debug status
     * @param  null|bool $debug status
     * @return void|bool
     */
    public function debug($debug = null)
    {
        if ($debug === null) {
            return self::$debug;
        }
        self::$debug = (bool)$debug;
    }

    /**
     * enable or disable test mode
     * @param  mixed $value     true|false|null
     * @return void|bool        if $value is null, return current value
     */
    public function testMode($value = null)
    {
        if ($value === null) {
            return $this->testMode;
        }
        $this->testMode = (bool)$value;
    }
}
