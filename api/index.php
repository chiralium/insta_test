<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    require_once("./lib/response.php");

    function routing( $path, $method ) {
        switch ($path) {
            case '/api':
            case '/':
                switch ($method) {
                    case 'GET':
                    case 'POST':
                        $response = new Response(
                            json_encode(
                                array(
                                    'api' => 'api/<method>',
                                    'time' => time(),
                                    'method' => $method
                                )
                            ),
                            200,
                            array(
                                'Content-Type' => 'application/json'
                            )
                        );
                        $response->response();
                        break;
                    }
                break;
        }
    }

    routing(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );