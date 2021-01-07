<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Func\Comments;

class CommentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function commentActionPost()
    {
        $comments = new Comments();
        
        $username = $this->di->session->get("username");

        $content = htmlentities($this->di->request->getPost("content"));
        $postid = htmlentities($this->di->request->getPost("postid"));
        $answerid = htmlentities($this->di->request->getPost("answerid"));

        $comments->newComment($content, $postid, $answerid, $username);

        return $this->di->response->redirect("post?id=" . $postid);
    }
    
    public function answerActionPost()
    {
        $comments = new Comments();
        
        $username = $this->di->session->get("username");

        $content = htmlentities($this->di->request->getPost("content"));
        $postid = htmlentities($this->di->request->getPost("postid"));

        $comments->newAnswer($content, $postid, $username);

        return $this->di->response->redirect("post?id=" . $postid);
    }
}
