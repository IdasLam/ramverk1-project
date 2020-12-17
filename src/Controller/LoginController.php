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

        $this->loggedIn = $this->di->session->get("loggedIn");

        // var_dump($this->loggedIn);

        $data = [];
        $this->di->get('page')->add('login/index', $data);
    }
    
    public function UserActionPost()
    {
        var_dump("login");

        // $this->di->get('page')->add('home/index', $data);
    }
}