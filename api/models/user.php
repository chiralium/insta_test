<?php
    require_once(__DIR__ . '/db.php');
    require_once(__DIR__ . '/../vendor/autoload.php');

    use \Firebase\JWT\JWT;

    class User {
        static $table = 'insta_users';

        public function __construct($name = null, $pwd = '')
        {
            $this->name = $name;
            $this->pwd = $pwd;

        }

        public static function get($where = array())
        {
            $db = new DB();
            return current($db->select(self::$table, $where));
        }

        public static function get_all()
        {
            $db = new DB();
            return $db->select(self::$table);
        }

        public static function authenticate( $username, $password )
        {
            $user = self::get(
                array(
                    'name' => $username,
                    'pwd' => $password
                )
            );

            if ( empty($user) ) return null;
            else {
                $token_payload = [
                    'iss' => 'localhost',
                    'sub' => 'secret',
                    'name' => $user->name,
                ];

                $key = 'big#psKT';
                $jwt = JWT::encode(
                    $token_payload,
                    base64_encode(
                        strtr(
                            $key,
                            '-_',
                            '+/'
                        )
                    ),
                    'HS256'
                );
                return $jwt;
            }
        }
    }