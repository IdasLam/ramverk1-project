<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $postdb = new \Ida\Database\Posts();
        $vote = new \Ida\Database\Func\Vote();
        $commentsdb = new \Ida\Database\Func\Comments();

        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");

        $id =  $this->di->request->getGet("id");
        
        $post = isset($id) ? $postdb->fetchPost($id) : $postdb->allLatestPosts();
        $comments = $commentsdb->postComments($id);

        $data = [
            "posts" => $post,
            "username" => $username,
            "vote" => $vote,
            "comments" => isset($id) ? $comments : null,
            "commentsdb" => $commentsdb,
            "gravatar" => isset($username) ? "https://www.gravatar.com/avatar/" . md5($email) : null
        ];

        if (isset($id)) {
            $this->di->get('page')->add('post/post', $data);
        } else {
            $this->di->get('page')->add('post/index', $data);   
        }

        return $this->di->get('page')->render($data);
    }
    
    public function newpostActionPost()
    {
        $post = new \Ida\Database\Posts();
        $input = $this->di->request->getPost();

        $tags = $this->di->request->getPost("tags");
        $content = $this->di->request->getPost("content");
        $username = $this->di->session->get("username");

        $post->newPosts($tags, $content, $username);

        return $this->di->response->redirect("home");
    }
}