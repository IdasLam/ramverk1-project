<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class TagController implements ContainerInjectableInterface
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
            "topTags" => $topTags,
        ];

        $this->di->get('page')->add('tags/index', $data);
        return $this->di->get('page')->render($data);
    }
}