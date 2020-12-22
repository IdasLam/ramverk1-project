<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class VoteController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        // Vote for post
        $votedb = new \Ida\Database\Func\Vote();
        $postsdb = new \Ida\Database\Posts();
        $input =  json_decode($this->di->request->getBody());

        $postid = $input->id;
        $type = $input->vote;
        $username = $this->di->session->get("username");

        $voted = $votedb->hasvotedPost($username, $postid);
        $userHasVoted = $voted === null ? null : intval($voted);

        if ($userHasVoted === $type) {
            // unvote
            $votedb->removeVotePost($postid, $type, $username);
        } elseif ($userHasVoted === null) {
            // update vote
            $votedb->votePost($postid, $type, $username);
        } else {
            $votedb->updateVotePost($postid, $type, $username);
        }

        $res = $postsdb->fetchPost($postid);

        return json_encode($res);

    }
}