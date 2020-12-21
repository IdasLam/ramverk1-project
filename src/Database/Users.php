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

    public function userExsists($username)
    {
        $sql = "SELECT * FROM users WHERE username LIKE ?";
        $res = $this->db->executeFetchAll($sql, [$username]);
        
        return count($res) > 0;
    }
    
    public function checkPassword($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username LIKE ?";
        $res = $this->db->executeFetch($sql, [$username]);

        $hash = $res->password;
        $valid = password_verify($password, $hash);
        return $valid;
    }

    public function createUser($email, $username, $password)
    {
        $nameExsists = $this->userExsists($username);

        if ($nameExsists == false) {
            $hased_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES
            (?, ?, ?)";
            $this->db->execute($sql, [$username, $email, $password]);

            return $this->userExsists($username);
        }

        return "Something went wrong";
    }
}