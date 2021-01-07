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
        $users = new \Ida\Database\Users();
        
        $username = $this->di->session->get("username");

        $topTags = $posts->topTags();

        $data = [
            "username" => $username ?? null,
            "posts" => $posts->latestPosts(),
            "topTags" => $topTags,
            "title" => "Tags"
        ];

        $this->di->get('page')->add('tags/index', $data);
        return $this->di->get('page')->render($data);
    }
}
