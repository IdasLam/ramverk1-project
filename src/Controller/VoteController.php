<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use \Ida\Database\Posts;
use \Ida\Database\Func\Vote;

class VoteController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        // Vote for post
        $votedb = new Vote();
        $postsdb = new Posts();
        $input =  json_decode($this->di->request->getBody());

        $postid = intval(htmlentities($input->id));
        $usernameInput = isset($input->username) ? htmlentities($input->username) : null;
        $type = intval(htmlentities($input->vote));
        $answerid = isset($input->answerid) ? intval(htmlentities($input->answerid)) : null;
        $commentid = isset($input->commentid) ? intval(htmlentities($input->commentid)) : null;

        $username = $this->di->session->get("username");

        if (!isset($answerid) && !isset($commentid)) {
            $voted = $votedb->hasvotedPost($username, $postid);
            $userHasVoted = $voted === null ? null : intval($voted);
    
            if ($userHasVoted === $type) {
                // unvote
                $votedb->removeVotePost($postid, $username);
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
                // var_dump("unvote");
                // unvote
                $votedb->removeVoteAnswer($postid, $answerid, $username);
            } elseif ($userHasVoted === null) {
                // update vote
                $votedb->voteAnswer($postid, $answerid, $type, $username);
            } else {
                $votedb->updateVoteAnswer($postid, $answerid, $type, $username);
            }

            $res = $votedb->answerStatus($postid, $answerid, $usernameInput);
        } elseif (isset($commentid)) {
            $voted = $votedb->hasVotedCommentPost($username, $postid, $answerid, $commentid);
            $userHasVoted = $voted === null ? null : intval($voted);

            if ($userHasVoted === $type) {
                // unvote
                $votedb->removeVoteComment($postid, $answerid, $username, $commentid);
            } elseif ($userHasVoted === null) {
                // update vote
                $votedb->voteComment($postid, $answerid, $type, $username, $commentid);
            } else {
                $votedb->updateVoteComment($postid, $answerid, $type, $username, $commentid);
            }

            $res = $votedb->commentStatus($postid, $answerid, $usernameInput, $commentid);
        }

        return json_encode($res);
    }
}
