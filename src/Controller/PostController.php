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

        $tags = htmlentities(trim($this->di->request->getGet("tag")));
        $id = htmlentities($this->di->request->getGet("id"));

        if ($tags !== "") {
            $post = $postdb->searchTags($tags);

            $data = [
                "posts" => $post,
                "username" => $username,
                "vote" => $vote,
                "gravatar" => isset($username) ? "https://www.gravatar.com/avatar/" . md5($email) : null,
                "title" => "Posts"
            ];

            $this->di->get('page')->add('post/index', $data);
        } else {
            $post = $id !== "" ? $postdb->fetchPost($id) : $postdb->allLatestPosts();
            $comments = $commentsdb->postComments($id);
    
            $data = [
                "posts" => $post,
                "username" => $username,
                "vote" => $vote,
                "comments" => isset($id) ? $comments : null,
                "commentsdb" => $commentsdb,
                "gravatar" => isset($username) ? "https://www.gravatar.com/avatar/" . md5($email) : null,
                "title" => "Posts"
            ];
    
            if ($id !== "") {
                $this->di->get('page')->add('post/post', $data);
            } else {
                $this->di->get('page')->add('post/index', $data);   
            }
        }

        return $this->di->get('page')->render($data);
    }
    
    public function newpostActionPost()
    {
        $post = new \Ida\Database\Posts();

        $tags = htmlentities($this->di->request->getPost("tags"));
        $content = htmlentities($this->di->request->getPost("content"));
        $username = $this->di->session->get("username");

        $post->newPosts($tags, $content, $username);

        return $this->di->response->redirect("home");
    }
    
    public function searchTagActionGet()
    {
        $post = new \Ida\Database\Posts();
        $search = htmlentities(trim($this->di->request->getGet("search")));

        return $this->di->response->redirect("post?tag=" . $search);
    }
}