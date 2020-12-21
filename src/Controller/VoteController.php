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
        $vote = new \Ida\Database\Func\Vote();
        $input =  json_decode($this->di->request->getBody());

        $postid = $input->id;
        $type = $input->votetype;
        $username = $this->di->session->get("username");

        // $voted = $this->hasvotedPost($username, $postid, null);

        // be able to unvote

        // if ($voted === true) {
        //     return $vote->removeVotePost($postid, $vote, $username);
        // }

        return $vote->votePost($postid, $type, $username);
    }
}