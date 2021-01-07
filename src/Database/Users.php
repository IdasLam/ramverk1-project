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
            $this->db->execute($sql, [$username, $email, $hased_password]);

            return $this->userExsists($username);
        }

        return "Something went wrong";
    }

    public function email($username)
    {
        $sql = "SELECT email FROM users WHERE username = ?";
        $res = $this->db->executeFetch($sql, [$username]);

        if ($res !== null) {
            return $res->email;
        }
    }

    public function getGravatar($username)
    {
        return "https://www.gravatar.com/avatar/" . md5($this->email($username));
    }

    public function emailExsists($email)
    {
        $sql = "SELECT * FROM users WHERE email LIKE ?";
        $res = $this->db->executeFetchAll($sql, [$email]);
        
        return count($res) > 0;
    }
    
    public function mostActiveUsers()
    {
        $vote = new \Ida\Database\Func\Vote();

        $sql = "SELECT username FROM users";
        $res = $this->db->executeFetchAll($sql, []);
        $topusers = [];

        foreach($res as $row) {
            $username = $row->username;
            // $count = 0;
            
            // $sql = "SELECT * FROM comments WHERE username = ?";
            // $res = $this->db->executeFetchAll($sql, [$username]);
            // $count += count($res);
            
            // $sql = "SELECT * FROM posts WHERE username = ?";
            // $res = $this->db->executeFetchAll($sql, [$username]);
            // $count += count($res);

            $topusers[$username] = $vote->profilePoints($username);
        }

        arsort($topusers);

        return array_slice($topusers, 0, 4);
    }

    public function changeUsername($oldusername, $newusername)
    {
        try {
            $sql = "UPDATE users SET username = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$newusername, $oldusername]);
            
            $sql = "UPDATE posts SET username = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$newusername, $oldusername]);
            
            $sql = "UPDATE comments SET username = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$newusername, $oldusername]);
            
            $sql = "UPDATE votes SET username = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$newusername, $oldusername]);
        } catch (Exception $e) {
            return 400;
        }

        return 200;
    }

    public function changePassword($username, $newPassword)
    {
        $hased_password = password_hash($newPassword, PASSWORD_DEFAULT);
        
        try {
            $sql = "UPDATE users SET password = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$hased_password, $username]);
        } catch (Exception $e) {
            return 400;
        }

        return 200;
    }

    public function changeEmail($username, $newEmail)
    {
        try {
            $sql = "UPDATE users SET email = ? WHERE username = ?";
            $res = $this->db->execute($sql, [$newEmail, $username]);
        } catch (Exception $e) {
            return 400;
        }

        return 200;
    }
}