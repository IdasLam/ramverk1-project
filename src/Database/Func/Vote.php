<?php
namespace Ida\Database\Func;
use Ida\Database\DB;

class Vote extends DB
{

    public function __construct()
    {
        parent::__construct();
    }

    public function vote($username, $postid, $commentid, $vote)
    {
        $commentid = $commentid === null ? null : intval($commentid);

        $sql = "INSERT INTO votes (username, postid, commentid, vote) VALUES (?, ?, ?, ?)";
        $res = $this->db->execute($sql, [$username, intval($postid), $commentid, $vote > 0 ? 1 : -1]);
    }
    
    public function hasvotedPost($username, $postid)
    {
        $sql = "SELECT * FROM votes WHERE postid = ? AND username = ? AND commentid IS NULL";
        $res = $this->db->executeFetch($sql, [$postid, $username]);

        return $res != null ? $res->vote : null;
    }

    public function votePost($postid, $vote, $username) {
        $this->vote($username, $postid, null, $vote);
    }

    public function removeVotePost($postid, $vote, $username) {
        $sql = "DELETE FROM votes WHERE id = ? AND username = ? AND commentid IS NULL";
        $res = $this->db->execute($sql, [$postid, $username]);
    }
    
    public function updateVotePost($postid, $vote, $username)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        ([$postid, $vote, $username, $voteScore]);
        $sql = "UPDATE votes SET vote = $voteScore WHERE postid = ? AND username = ? AND commentid IS NULL";

        $this->db->execute($sql, [$postid, $username]);
    }
    
    public function hasVotedAnswerPost($username, $postid, $answerid)
    {
        $sql = "SELECT * FROM votes WHERE postid = ? AND username = ? AND answerid = ? AND commentid IS NULL";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid]);

        return $res != null ? $res->vote : null;
    }
    
    public function hasVotedCommentPost($username, $postid, $answerid, $commentsid)
    {
        $sql = "SELECT * FROM votes WHERE postid = ? AND username = ? AND answerid = ? AND commentid IS NULL";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid]);

        return $res != null ? $res->vote : null;
    }
}