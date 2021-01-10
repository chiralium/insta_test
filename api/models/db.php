<?php
    class DB
    {
        public function __construct()
        {
            global $CFG;
            $this->conn = mysqli_connect(
                $CFG->db_host,
                $CFG->db_user,
                $CFG->db_password,
                $CFG->db_name
            );
        }

        public function check_conn()
        {
            var_dump($this->conn);
        }

        public function select( $table, $where = array() ) {
            /*
             * $where = array(
             *  'id' => 0,
             *  'name' => 'admin'
             * )
             */

            $where_statement_template = "%s = %s AND "; $where_statement = "";

            foreach ( $where as $field => $val ) {
                $where_statement .= sprintf(
                    $where_statement_template,
                    $field,
                    gettype($val) == 'string' ? "'$val'" : $val
                );
            }
            if ( $where_statement ) {
                $where_statement = substr($where_statement, 0, -5);
                $sql_select_statement = "SELECT * FROM $table WHERE $where_statement";
            } else $sql_select_statement = "SELECT * FROM $table";

            $result = mysqli_query($this->conn, $sql_select_statement);

            $std_result = array();
            while ($std = $result->fetch_object()) $std_result[] = $std;

            return $std_result;
        }
    }