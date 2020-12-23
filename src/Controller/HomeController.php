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

        $topTags = $posts->topTags();
        // popular tags
        $topUsers = $users->mostActiveUsers();

        $data = [
            "username" => $username ?? null,
            "posts" => $posts->latestPosts(),
            "vote" => $vote,
            "topUsers" => $topUsers,
            "topTags" => $topTags,
        ];

        $this->di->get('page')->add('home/index', $data);
        return $this->di->get('page')->render($data);
    }
}