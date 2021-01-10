<?php

class Response
{
    public function __construct( $body = null, $status = 200, $headers = null )
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->status = $status;
    }

    public function response()
    {
        if ( $this->headers )
            foreach ($this->headers as $header => $value) header(sprintf("%s:%s", $header, $value));
        echo $this->body;
    }

    public function cors($domains = array()) {
        if ( empty($domains) ) $val = '*';
        else $val = implode(',', $domains);
        header("Access-Control-Allow-Headers: $val");
    }
}