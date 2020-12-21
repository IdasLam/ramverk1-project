<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $posts = new \Ida\Database\Posts();
        $vote = new \Ida\Database\Func\Vote();
        $users = new \Ida\Database\Users();
        $username = $this->di->session->get("username");

        $data = [
            "username" => $username ?? null,
            "posts" => $posts->latestPosts(),
            "vote" => $vote,
            "gravatar" => "https://www.gravatar.com/avatar/" . md5($users->email($username))
        ];

        $this->di->get('page')->add('home/index', $data);
        return $this->di->get('page')->render($data);
    }
}