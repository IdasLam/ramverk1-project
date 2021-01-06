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
        $userdb = new \Ida\Database\Users();
        $vote = new \Ida\Database\Func\Vote();

        $user = $this->di->request->getGet("user");
        
        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");

        if (!isset($user)) {
            return $this->di->response->redirect("home");
        }
        
        $data = [
            "currentUser" => $username,
            "username" => $user,
            "points" => $vote->profilePoints($user),
            "posts" => $posts->profilePost($user),
            "comments" => $comments->profileComments($user),
            "answers" => $comments->profileAnswers($user),
            "gravatar" => $userdb->getGravatar($user),
            "title" => $user,
            "vote" => $vote,
        ];

        if ($user === $username) {
            $data["email"] = $email;
        } else {
            $data["email"] = $userdb->email($user);
        }
        
        $this->di->get('page')->add('profile/index', $data);
        return $this->di->get('page')->render($data);
    }

    public function editActionGet()
    {
        $edit = $this->di->request->getGet("type");
        
        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");

        $data = [
            "username" => $username,
            "email" => $email,
            "gravatar" => "https://www.gravatar.com/avatar/" . md5($email),
            "title" => "Edit"
        ];

        if (isset($edit)) {
            $error = $this->di->session->get("userEditError");

            $data["edit"] = $edit;
            $data["error"] = isset($error) ? $error : null;

            if (isset($error)) {
                $this->di->session->delete("userEditError");
            }

            $this->di->get('page')->add('profile/edit-specific', $data);
            return $this->di->get('page')->render($data);

        } else {
            $this->di->get('page')->add('profile/edit', $data);
            return $this->di->get('page')->render($data);
        }
    }

    public function editActionPost()
    {
        $users = new \Ida\Database\Users();
        
        $edit = $this->di->request->getPost("edit");
        $new = htmlentities($this->di->request->getPost($edit));

        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");
        
        if ($edit === "username") {
            $alreadyExsist = $users->userExsists($new);

            if ($alreadyExsist === true) {
                $this->di->session->set("userEditError", "Username is unavailable.");
                return $this->di->response->redirect("profile/edit?type=" . $edit);
            }

            $res = $users->changeUsername($username, $new);

            if ($res === 200) {
                $this->di->session->set("username", $new);
            }
            
        } elseif ($edit === "email") {
            $alreadyExsist = $users->emailExsists($new);
            
            if ($alreadyExsist === true) {
                $this->di->session->set("userEditError", "Email is unavailable.");
                return $this->di->response->redirect("edit?type=" . $edit);
            }
            
            $res = $users->changeEmail($username, $new);
            
            if ($res === 200) {
                $this->di->session->set("email", $new);
            }
            
        } else {
            $users->changePassword($username, $new);
        }

        return $this->di->response->redirect("profile");
    }
}