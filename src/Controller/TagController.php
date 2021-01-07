<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Posts;
use \Ida\Database\Users;

class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $posts = new Posts();

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
