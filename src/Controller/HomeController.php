<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $userid;

    public function indexActionGet()
    {
        $posts = new \Ida\Database\Posts();
        $this->userid = $this->di->session->get("userId");
        $vote = new \Ida\Database\Func\Vote();


        $data = [
            "userid" => $this->loggedIn ?? null,
            "posts" => $posts->latestPosts(),
            "vote" => $vote
        ];

        $this->di->get('page')->add('home/index', $data);
        return $this->di->get('page')->render($data);
    }
}