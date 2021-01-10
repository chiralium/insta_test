<?php
    $CFG = new stdClass();

    $CFG->db_host = "db";
    $CFG->db_port = "3306";
    $CFG->db_password = "root";
    $CFG->db_user = "root";
    $CFG->db_name = 'insta';

    require_once(__DIR__ . "/lib/response.php");
    require_once(__DIR__ . '/models/user.php');
