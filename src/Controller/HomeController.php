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
        $posts = new \Ida\Database\Posts();
        $this->loggedIn = $this->di->session->get("loggedIn");

        $data = ["loggedIn" => $this->loggedIn ?? null, "posts" => $posts->latestPosts()];
        $this->di->get('page')->add('home/index', $data);
    }
}