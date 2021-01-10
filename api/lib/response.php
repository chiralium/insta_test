<?php

class Response
{
    public function __construct( $body = null, $status = "200 OK", $headers = null )
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->status = $status;
    }

    public function response()
    {
        if ( $this->headers )
            foreach ($this->headers as $header => $value) header(sprintf("%s:%s", $header, $value));

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization");
        echo $this->body;
    }
}