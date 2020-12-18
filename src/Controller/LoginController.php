<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class LoginController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $loggedIn;

    public function indexActionGet()
    {
        $db = new \Ida\Database\DB();

        // $this->loggedIn = $this->di->session->get("loggedIn");

        // var_dump($this->loggedIn);

        $data = ["title" => "Login"];
        $this->di->get('page')->add('login/index', $data);
        return $this->di->get('page')->render($data);
    }
    
    public function registerActionGet()
    {
        // $this->loggedIn = $this->di->session->get("loggedIn");

        // var_dump($this->loggedIn);

        $data = ["title" => "Register", "createError" => $this->di->session->get("createError")];
        $this->di->get('page')->add('login/register', $data);
        return $this->di->get('page')->render($data);
    }
    
    // public function UserActionPost()
    // {
    //     var_dump("login");

    //     // $this->di->get('page')->add('home/index', $data);
    // }

    public function signupActionPost()
    {
        $user = new \Ida\Database\User();

        $username = htmlentities($this->di->request->getPost("username"));
        $password = htmlentities($this->di->request->getPost("password"));
        $email = htmlentities($this->di->request->getPost("email"));
        
        $res = $user->createUser($email, $username, $password);

        if ($res) {
            $this->di->session->set("username", $username);
        } elseif ($res === "something went wrong") {
            $this->di->session->set("createError", $res);
        }

        // $this->di->get('page')->add('home/index', $data);
    }
}