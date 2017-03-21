<?php

namespace Oktey\Api;

class Response
{
    private $status;
    private $success;
    private $body;

    public function __construct($request, $response)
    {
        $this->request = $request;

        if ($response) {
            $this->status = $response->getStatusCode();
            if (Client::$debug) {
                $this->body = $response->getBody()->getContents();
            } else {
                $this->body = json_decode($response->getBody(), true);
            }
            $this->success = floor($this->status / 100) == 2 ? true : false;
        }
    }

    /**
     * Status Getter
     * return the http status code
     * @return int status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Status Getter
     * return the http status code
     * @return int status
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Count getter
     * return the resulting array size
     * @return int count
     */
    public function getCount()
    {
        return !empty($this->body['count']) ? (int)$this->body['count'] : 0;
    }

    /**
     * Data getter
     * return the datas
     * @return int count
     */
    public function getData()
    {
        return !empty($this->body['data']) ? (array)$this->body['data'] : [];
    }

    /**
     * Total getter
     * return the total count of all results
     * @return int count
     */
    public function getTotal()
    {
        return $this->body['Total'];
    }

    /**
     * Success getter
     * @return boolean true is return code is 2**
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * Error message getter
     * @return string message error
     */
    public function getMessageError()
    {
        return !empty($this->body['error_message']) ? $this->body['error_message'] : null;
    }
}
