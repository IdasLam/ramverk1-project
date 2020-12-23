<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class ProfileController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $posts = new \Ida\Database\Posts();
        $comments = new \Ida\Database\Func\Comments();
        
        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");

        if (!isset($username)) {
            return $this->di->response->redirect("login");
        } 

        $data = [
            "username" => $username,
            "email" => $email,
            "posts" => $posts->profilePost($username),
            "comments" => $comments->profileComments($username),
            "gravatar" => "https://www.gravatar.com/avatar/" . md5($email)
        ];

        $this->di->get('page')->add('profile/index', $data);
        return $this->di->get('page')->render($data);
    }
}