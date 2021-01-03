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

        $postid = intval(htmlentities($input->id));
        $type = intval(htmlentities($input->vote));
        $answerid = isset($input->answerid) ? intval(htmlentities($input->answerid)) : null;
        $commentid = isset($input->commentid) ? intval(htmlentities($input->commentid)) : null;

        $username = $this->di->session->get("username");

        if (!isset($answerid) && !isset($commentid)) {
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
        } elseif (isset($answerid) && !isset($commentid)) {
            $voted = $votedb->hasVotedAnswerPost($username, $postid, $answerid);
            $userHasVoted = $voted === null ? null : intval($voted);

            if ($userHasVoted === $type) {
                // unvote
                $votedb->removeVoteAnswer($postid, $answerid, $type, $username);
            } elseif ($userHasVoted === null) {
                // update vote
                $votedb->voteAnswer($postid, $answerid, $type, $username);
            } else {
                $votedb->updateVoteAnswer($postid, $answerid, $type, $username);
            }

            $res = $votedb->answerStatus($postid, $answerid, $username);
        } elseif (isset($commentid)) {
            $voted = $votedb->hasVotedCommentPost($username, $postid, $answerid, $commentid);
            $userHasVoted = $voted === null ? null : intval($voted);

            // var_dump($voted, $userHasVoted);

            if ($userHasVoted === $type) {
                // unvote
                $votedb->removeVoteComment($postid, $answerid, $type, $username, $commentid);
            } elseif ($userHasVoted === null) {
                // update vote
                $votedb->voteComment($postid, $answerid, $type, $username, $commentid);
            } else {
                $votedb->updateVoteComment($postid, $answerid, $type, $username, $commentid);
            }

            $res = $votedb->commentStatus($postid, $answerid, $username, $commentid);
        }

        return json_encode($res);
    }

    // public function AnswerActionPost()
    // {
    //     // Vote for answer
    //     $votedb = new \Ida\Database\Func\Vote();
    //     $postsdb = new \Ida\Database\Posts();
    //     $input =  json_decode($this->di->request->getBody());

    //     $postid = htmlentities($input->postid);
    //     $type = intval(htmlentities($input->vote));
    //     $answerid = htmlentities($input->answerid);
    //     $username = $this->di->session->get("username");

    //     $voted = $votedb->hasVotedAnswerPost($username, $postid, $answerid);
    //     $userHasVoted = $voted === null ? null : intval($voted);

    //     if ($userHasVoted === $type) {
    //         // unvote
    //         $votedb->removeVoteAnswer($postid, $answerid, $type, $username);
    //     } elseif ($userHasVoted === null) {
    //         // update vote
    //         $votedb->voteAnswer($postid, $answerid, $type, $username);
    //     } else {
    //         $votedb->updateVoteAnswer($postid, $answerid, $type, $username);
    //     }

    //     $res = $votedb->answerStatus($postid);

    //     return json_encode($res);
    // }
}