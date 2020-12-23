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

        // $topTags = $posts->topTags();
        // // popular tags
        // $topUsers = $users->mostActiveUsers();

        $data = [
            "username" => $username,
            "email" => $email,
            "posts" => $posts->profilePost($username),
            "comments" => $comments->profileComments($username),
            // "vote" => $vote,
            // "topUsers" => $topUsers,
            // "topTags" => $topTags,
            "gravatar" => "https://www.gravatar.com/avatar/" . md5($email)
        ];

        $this->di->get('page')->add('profile/index', $data);
        return $this->di->get('page')->render($data);
    }
}