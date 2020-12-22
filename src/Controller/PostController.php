<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        $post = new \Ida\Database\Post();
        $input =  json_decode($this->di->request->getBody());

        // return $vote->votePost($postid, $type, $username);
    }
    
    public function postActionPost()
    {
        $post = new \Ida\Database\Post();
        $input =  json_decode($this->di->request->getBody());

        // return $vote->votePost($postid, $type, $username);
    }
}