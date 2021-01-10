<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    require_once("./config.php");

    function routing( $path, $method )
    {
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
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();
                }
                break;
            case '/api/users':
                switch ($method) {
                    case 'GET':
                        $response = new Response(
                            json_encode(
                                User::get_all()
                            ),
                            200,
                            array(
                                'Content-Type' => 'application/json'
                            )
                        );
                        $response->response();
                        break;
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();
                }
                break;
            case '/api/auth':
                switch ($method) {
                    case 'POST':
                        $data = json_decode(file_get_contents('php://input'));
                        $jwt = User::authenticate($data->username, $data->password);
                        if ( !$jwt ) {
                            $response = new Response(
                                json_encode(
                                    array(
                                        "error" => "User not found!"
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

                        $response = new Response(
                            json_encode(
                                array(
                                    'jwt' => $jwt
                                )
                            ),
                            200,
                            array(
                                'Content-Type' => 'application/json'
                            )
                        );
                        $response->response();
                        break;
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();
                    }
                    break;
            case '/api/home':
                switch ($method) {
                    case 'GET':
                        $headers = apache_request_headers();
                        if ( !array_key_exists('Authorization', $headers ) ) {
                            $response = new Response(
                                json_encode(
                                    array(
                                        'error' => 'Authorization headers is not provided!'
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

                        $jwt = $headers['Authorization'];
                        $user = User::is_authorized($jwt);

                        if ( !$user ) {
                            $response = new Response(
                                json_encode(
                                    array(
                                        'error' => 'Authorization failed!'
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

                        $response = new Response(
                            json_encode(
                                $user
                            ),
                            200,
                            array(
                                'Content-Type' => 'application/json'
                            )
                        );
                        $response->response();
                        break;
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();

                }
        }
    }

    routing(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );