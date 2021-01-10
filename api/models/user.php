<?php
    require_once(__DIR__ . '/db.php');
    require_once(__DIR__ . '/../vendor/autoload.php');

    use \Firebase\JWT\JWT;

    class User {
        static $table = 'insta_users';
        static $key = 'big#psKT';

        public function __construct($name = null, $pwd = '', $id = null)
        {
            $this->name = $name;
            $this->pwd = $pwd;
            $this->db = self::get(
                array(
                    'name' => $this->name,
                    'pwd' => $this->pwd
                )
            );
        }

        public function add_contacts($contact_id)
        {
            $db = new DB();
            $is_exist = $db->select(
                'insta_user_contacts',
                array(
                    'contact_id' => $contact_id,
                    'user_id' => $this->db->id
                )
            );

            if ( $is_exist ) return false;

            $result = $db->insert(
                'insta_user_contacts',
                array(
                    'contact_id' => $contact_id,
                    'user_id' => $this->db->id
                )
            );

            return $result;
        }

        public static function get_contacts($user_id)
        {
            $db = new DB();
            $contacts = $db->raw(
                    "
                        SELECT c.* FROM insta_user_contacts uc 
                        INNER JOIN insta_contacts c ON uc.contact_id = c.id
                        WHERE user_id = $user_id 
                    "
            );
            return $contacts;
        }

        public function set()
        {
            $db = new DB();
            $is_exist = $db->select(
                self::$table,
                array(
                    'name' => $this->name
                )
            );

            if ($is_exist) return false;

            return $db->insert(
                self::$table,
                array(
                    'name' => $this->name,
                    'pwd' => $this->pwd
                )
            );
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
                    'name' => $user->name,
                    'id' => $user->id,
                    'pwd' => $user->pwd
                ];


                $jwt = JWT::encode(
                    $token_payload,
                    base64_decode(
                        strtr(
                            self::$key,
                            '-_',
                            '+/'
                        )
                    ),
                    'HS256'
                );
                return $jwt;
            }
        }

        public static function is_authorized($jwt)
        {
            try {
                $user = JWT::decode(
                    $jwt,
                    base64_decode(
                        strtr(
                            self::$key,
                            '-_',
                            '+/'
                        )
                    ),
                    ['HS256']
                );
            } catch (Exception $e) {
                return null;
            }

            return $user;
        }
    }