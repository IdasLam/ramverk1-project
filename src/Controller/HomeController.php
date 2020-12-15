<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $loggedIn;

    public function indexActionGet()
    {
        $users = new \Ida\Database\Users();
        $this->loggedIn = $this->di->session->get("loggedIn");

        var_dump($users->allUsers());

        $data = [];
        $this->di->get('page')->add('home/index', $data);
    }
}