<?php

namespace Ida\Database;

class DB
{
    protected $db;

    public function __construct()
    {
        $options = [
            "dsn"             => "sqlite:" . ANAX_INSTALL_PATH . "/data/db.sqlite",
            "username"        => "root",
            "password"        => "root",
            "driver_options"  => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
            ],
            "fetch_mode"      => \PDO::FETCH_OBJ,
            "table_prefix"    => null,
            "session_key"     => "Anax\Database",
            "emulate_prepares" => false,
        
            // True to be very verbose during development
            "verbose"         => false,
        
            // True to be verbose on connection failed
            "debug_connect"   => true,
        ];

        $this->db = new \Anax\Database\Database();
        $this->db->setOptions($options);

        $this->db->connect();
    }
}