<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Posts;
use \Ida\Database\Func\Vote;
use \Ida\Database\Users;

class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $posts = new Posts();
        $vote = new Vote();
        $users = new Users();
        
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
            "title" => "Home"
        ];

        $this->di->get('page')->add('home/index', $data);
        return $this->di->get('page')->render($data);
    }
}
