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
            $this->body = json_decode($response->getBody(), true);
            $this->success = floor($this->status / 100) == 2 ? true : false;
        }
    }

}
