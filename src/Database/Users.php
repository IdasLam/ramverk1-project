<?php

namespace Ida\Database;

class Users extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function allUsers()
    {
        $sql = "SELECT * FROM users";

        return $this->db->executeFetchAll($sql);
    }
}