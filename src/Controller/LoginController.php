<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Users;

class LoginController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $data = ["title" => "Login", "di" => $this->di];
        $this->di->get('page')->add('login/index', $data);
        return $this->di->get('page')->render($data);
    }
    
    public function registerActionGet()
    {
        $data = ["title" => "Register", "di" => $this->di, "createError" => $this->di->session->get("createError")];
        $this->di->get('page')->add('login/register', $data);
        $this->di->session->delete("createError");
        return $this->di->get('page')->render($data);
    }
    
    public function userloginActionPost()
    {
        $user = new Users();

        $username = htmlentities($this->di->request->getPost("username"));
        $password = htmlentities($this->di->request->getPost("password"));

        $usernameExsists = $user->userExsists($username);

        if ($usernameExsists) {
            $userValid = $user->checkPassword($username, $password);

            if ($userValid) {
                $this->di->session->set("username", $username);
                $this->di->session->set("loggedin", true);
                $this->di->session->set("email", $user->email($username));

                return $this->di->response->redirect("home");
            } else {
                $this->di->session->set("loginError", "Username or password was not valid.");
                return $this->di->response->redirect("login");
            }
        }
        
        $this->di->session->set("loginError", "Username does not exsist.");
        return $this->di->response->redirect("login");
    }

    public function signupActionPost()
    {
        $user = new Users();

        $username = trim(htmlentities($this->di->request->getPost("username")));
        $password = trim(htmlentities($this->di->request->getPost("password")));
        $email = trim(htmlentities($this->di->request->getPost("email")));
        
        $exsists = $user->userExsists($username) || $user->emailExsists($email);

        if ($exsists) {
            $this->di->session->set("createError", "Username or email already exists");
            return $this->di->response->redirect("login/register");
        }

        $res = $user->createUser($email, $username, $password);

        if ($res) {
            $this->di->session->set("username", $username);
            $this->di->session->set("loggedin", true);
            $this->di->session->set("email", $email);

            return $this->di->response->redirect("home");
        } elseif ($res === "something went wrong") {
            $this->di->session->set("createError", $res);
        }
        
        return $this->di->response->redirect("login/register");
    }

    public function logoutActionPost()
    {
        $this->di->get("session")->destroy();
        return $this->di->response->redirect("home");
    }
}
