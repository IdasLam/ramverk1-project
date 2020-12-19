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

        $data = ["title" => "Login", "di" => $this->di];
        $this->di->get('page')->add('login/index', $data);
        return $this->di->get('page')->render($data);
    }
    
    public function registerActionGet()
    {
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
        $user = new \Ida\Database\Users();

        $username = htmlentities($this->di->request->getPost("username"));
        $password = htmlentities($this->di->request->getPost("password"));
        $email = htmlentities($this->di->request->getPost("email"));
        
        $res = $user->createUser($email, $username, $password);

        if ($res) {
            $this->di->get("session")->start();
            $this->di->session->set("username", $username);
            $this->di->session->set("loggedin", true);
            return $this->di->response->redirect("home");
        } elseif ($res === "something went wrong") {
            $this->di->session->set("createError", $res);
        } else {
            $this->di->session->set("createError", "Username already exsists");
        }
        
        return $this->di->response->redirect("register");
    }
}