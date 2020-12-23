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

    public function email($username)
    {
        $sql = "SELECT email FROM users WHERE username = ?";
        $res = $this->db->executeFetch($sql, [$username]);
        return $res->email;
    }
    
    public function mostActiveUsers()
    {
        $sql = "SELECT username FROM users";
        $res = $this->db->executeFetchAll($sql, []);
        $topusers = [];

        foreach($res as $row) {
            $username = $row->username;
            $count = 0;
            
            $sql = "SELECT * FROM comments WHERE username = ?";
            $res = $this->db->executeFetchAll($sql, [$username]);
            $count += count($res);
            
            $sql = "SELECT * FROM posts WHERE username = ?";
            $res = $this->db->executeFetchAll($sql, [$username]);
            $count += count($res);

            $topusers[$username] = $count;
        }

        arsort($topusers);

        return array_slice($topusers, 0, 4);
    }
}