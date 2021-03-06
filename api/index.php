<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    require_once("./config.php");

    function is_loggedin()
    {
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
            return false;
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
            return false;
        }
        return $user;
    }

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
                    case 'GET':case 'OPTIONS':
                        $user = is_loggedin();
                        if (!$user) break;

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
                break;
            case '/api/register':
                switch ($method) {
                    case 'POST':case 'OPTIONS':
                        $data = json_decode(file_get_contents('php://input'));
                        if ( $data->username && $data->password ) {
                            $user = new User(
                                $data->username,
                                $data->password
                            );
                            $result = $user->set();

                            if ( !$result ) {
                                $response = new Response(
                                    json_encode(
                                        array(
                                            'error' => 'User is already exists!'
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
                                        'ok' => 'User is created!'
                                    )
                                ),
                                200,
                                array(
                                    'Content-Type' => 'application/json'
                                )
                            );
                            $response->response();
                        } else {
                            $response = new Response(
                                json_encode(
                                    array(
                                        'error' => 'Credentials is not provided'
                                    )
                                ),
                                200,
                                array(
                                    'Content-Type' => 'application/json'
                                )
                            );
                            $response->response();
                        }
                        break;
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();
                }
                break;
            case '/api/contacts':
                switch ($method) {
                    case 'GET':case 'OPTIONS':
                        if (!is_loggedin()) break;
                        $db = new DB();
                        $contacts = $db->select(
                            'insta_contacts'
                        );
                        $response = new Response(
                            json_encode(
                                $contacts
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
            case '/api/get_user_contacts':
                switch ($method) {
                    case 'GET':case 'OPTIONS':
                        $user = is_loggedin();
                        if (!$user) break;
                        else {
                            $contacts = User::get_contacts($user->id);
                            $response = new Response(
                                json_encode(
                                    $contacts
                                ),
                                200,
                                ['Content-Type' => 'application/json']
                            );
                            $response->response();
                        }
                        break;
                    default:
                        $response = new Response(
                            '',
                            404
                        );
                        $response->response();
                }
                break;
            case '/api/contact_add':
                switch ($method) {
                    case 'POST':case 'OPTIONS':
                        $user = is_loggedin();
                        if ( !$user ) break;
                        $data = json_decode(
                            file_get_contents("php://input")
                        );

                        if ( !$data->contact_id ) {
                            $response = new Response(
                                json_encode(
                                    array(
                                        "error" => "Credentials of contact is not provided",
                                    )
                                ),
                                200,
                                array(
                                    "Content-Type" => "application/json"
                                )
                            );
                            $response->response();
                            break;
                        }

                        $user = new User($user->name, $user->pwd);
                        $user->add_contacts($data->contact_id);
                        $response = new Response(
                            json_encode(
                                array(
                                    "ok" => "Contact added!"
                                )
                            ),
                            200,
                            array(
                                "Content-Type" => 'application/json'
                            )
                        );
                        $response->response();
                        break;
                }
                break;
            default:
                $response = new Response(
                    '',
                    404
                );
                $response->response();
        }
    }

    routing(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );