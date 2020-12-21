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
    
    public function userloginActionPost()
    {
        $user = new \Ida\Database\Users();

        $username = htmlentities($this->di->request->getPost("username"));
        $password = htmlentities($this->di->request->getPost("password"));

        $usernameExsists = $user->userExsists($username);

        if ($usernameExsists) {
            $userValid = $user->checkPassword($username, $password);

            $this->di->get("session")->start();
            if ($userValid) {
                $this->di->session->set("username", $username);
                $this->di->session->set("loggedin", true);
            } else {
                $this->di->session->set("loginError", "Username or password was not valid.");
                return $this->di->response->redirect("login");
            }
        }

        return $this->di->response->redirect("home");
    }

    public function signupActionPost()
    {
        $user = new \Ida\Database\Users();

        $username = htmlentities($this->di->request->getPost("username"));
        $password = htmlentities($this->di->request->getPost("password"));
        $email = htmlentities($this->di->request->getPost("email"));
        
        $res = $user->createUser($email, $username, $password);

        $this->di->get("session")->start();
    
        if ($res) {
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

    public function logoutActionPost() {
        $this->di->get("session")->destroy();
    }
}