<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Posts;
use \Ida\Database\Func\Vote;
use \Ida\Database\Users;
use \Ida\Database\Func\Comments;

class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionGet()
    {
        $postdb = new Posts();
        $vote = new Vote();
        $commentsdb = new Comments();
        $users = new Users();

        $username = $this->di->session->get("username");
        $email = $this->di->session->get("email");

        $tags = htmlentities(trim($this->di->request->getGet("tags")));
        $id = htmlentities($this->di->request->getGet("id"));

        if ($tags !== "") {
            $post = $postdb->searchTags($tags);

            $data = [
                "posts" => $post,
                "username" => $username,
                "vote" => $vote,
                "gravatar" => isset($username) ? "https://www.gravatar.com/avatar/" . md5($email) : null,
                "title" => "Posts",
                "usersdb" => $users,
                "commentsdb" => $commentsdb,
            ];

            $this->di->get('page')->add('post/index', $data);
        } else {
            $sort = htmlentities($this->di->request->getGet("sort-by"));

            $post = $id !== "" ? $postdb->fetchPost($id) : $postdb->allLatestPosts();

            if (empty($post)) {
                return $this->di->response->redirect("post");
            }

            if ($sort === "" || $sort === "default") {
                $comments = $id !== "" ? $commentsdb->postAnswers($id, $post->answer) : null;
            } elseif ($sort === "latest") {
                $comments = $id !== "" ? $commentsdb->dateAnswers($id, "DESC") : null;
            } elseif ($sort === "oldest") {
                $comments = $id !== "" ? $commentsdb->dateAnswers($id, "ASC") : null;
            } elseif ($sort === "upvotes") {
                $comments = $id !== "" ? $commentsdb->orderUpvotesAnswers($id, "DESC") : null;
            } else {
                $comments = $id !== "" ? $commentsdb->orderUpvotesAnswers($id, "ASC") : null;
            }
            
            $data = [
                "posts" => $post,
                "username" => $username,
                "vote" => $vote,
                "answers" => isset($id) ? $comments : null,
                "commentsdb" => $commentsdb,
                "gravatar" => isset($username) ? "https://www.gravatar.com/avatar/" . md5($email) : null,
                "title" => "Posts",
                "usersdb" => $users
            ];
    
            if ($id !== "") {
                $data["id"] = $id;
                $this->di->get('page')->add('post/post', $data);
            } else {
                $this->di->get('page')->add('post/index', $data);
            }
        }

        return $this->di->get('page')->render($data);
    }
    
    public function newpostActionPost()
    {
        $post = new Posts();

        $tags = trim(htmlentities($this->di->request->getPost("tags")));
        $content = htmlentities($this->di->request->getPost("content"));
        $username = $this->di->session->get("username");

        $tags = $tags === "" ? null : $tags;

        $post->newPosts($tags, $content, $username);

        return $this->di->response->redirect("post");
    }
    
    public function searchTagActionGet()
    {
        $search = trim(htmlentities(trim($this->di->request->getGet("search"))));

        return $this->di->response->redirect("post?tags=" . $search);
    }

    public function markAnswerActionPost()
    {
        $post = new Posts();

        $postid = htmlentities($this->di->request->getPost("postid"));
        $answerid = htmlentities($this->di->request->getPost("answerid"));
        $currentAnswer = htmlentities($this->di->request->getPost("currentAnswer"));

        if ($answerid === $currentAnswer) {
            $post->unsetAnswer($postid);
        } else {
            $post->setAnswer($postid, $answerid);
        }

        return $this->di->response->redirect("post?id=" . $postid);
    }
}
