<?php
namespace Ida\Database\Func;
use Ida\Database\DB;

class Vote extends DB
{

    public function __construct()
    {
        parent::__construct();
    }

    public function vote($username, $postid, $answerid, $commentid, $vote)
    {
        $commentid = $commentid === null ? null : intval($commentid);
        $answerid = $answerid === null ? null : intval($answerid);

        if (!isset($answerid) && !isset($commentid)) {
            $sql = "INSERT INTO votes (username, postid, vote) VALUES (?, ?, ?)";
            $res = $this->db->execute($sql, [$username, intval($postid), $vote > 0 ? 1 : -1]);
        } elseif (isset($answerid) && !isset($commentid)){
            $sql = "INSERT INTO answerCommentVotes (username, postid, answerid, commentid, vote) VALUES (?, ?, ?, ?, ?)";
            $res = $this->db->execute($sql, [$username, intval($postid), $answerid, $commentid, $vote > 0 ? 1 : -1]);
        } elseif (isset($commentid)) {
            $sql = "INSERT INTO answerCommentVotes (username, commentid, postid, vote) VALUES (?, ?, ?, ?)";
            $res = $this->db->execute($sql, [$username, $commentid, intval($postid), $vote > 0 ? 1 : -1]);
        }
    }
    
    public function hasvotedPost($username, $postid)
    {
        $sql = "SELECT * FROM votes WHERE postid = ? AND username = ?";
        $res = $this->db->executeFetch($sql, [$postid, $username]);

        return $res != null ? $res->vote : null;
    }

    public function votePost($postid, $vote, $username) {
        $this->vote($username, $postid, null, null, $vote);
    }

    public function removeVotePost($postid, $vote, $username) {
        $sql = "DELETE FROM votes WHERE id = ? AND username = ?";
        $res = $this->db->execute($sql, [$postid, $username]);
    }
    
    public function updateVotePost($postid, $vote, $username)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        $sql = "UPDATE votes SET vote = $voteScore WHERE postid = ? AND username = ?";

        $this->db->execute($sql, [$postid, $username]);
    }
    
    public function hasVotedAnswerPost($username, $postid, $answerid)
    {
        $sql = "SELECT * FROM answerCommentVotes WHERE postid = ? AND username = ? AND answerid = ?";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid]);

        return $res != null ? $res->vote : null;
    }

    public function voteAnswer($postid, $answerid, $vote, $username) {
        $this->vote($username, $postid, $answerid, null, $vote);
    }

    public function removeVoteAnswer($postid, $answerid, $vote, $username) {
        $sql = "DELETE FROM answerCommentVotes WHERE id = ? AND username = ? AND answerid = ? AND commentid IS NULL";
        $res = $this->db->execute($sql, [$postid, $username, $answerid]);
    }

    public function updateVoteAnswer($postid, $answerid, $vote, $username)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        $sql = "UPDATE answerCommentVotes SET vote = $voteScore WHERE postid = ? AND username = ? AND commentid IS NULL AND answerid = ?";

        $this->db->execute($sql, [$postid, $username, $answerid]);
    }

    public function answerStatus($postid, $answerid, $username)
    {
        $sql = "SELECT answers.*, SUM(answerCommentVotes.vote) as score FROM answers LEFT OUTER JOIN answerCommentVotes ON answerCommentVotes.answerid = answers.id WHERE answers.postid = ? AND answers.id = ? AND answers.username = ?";
        $res = $this->db->executeFetch($sql, [$postid, $answerid, $username]);

        return $res;
    }
    
    public function hasVotedCommentPost($username, $postid, $answerid, $commentid)
    {
        $sql = "SELECT * FROM answerCommentVotes WHERE postid = ? AND username = ? AND answerid = ? AND commentid = ?";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid, $commentid]);

        return $res != null ? $res->vote : null;
    }

    public function voteComment($postid, $answerid, $vote, $username, $commentid) {
        $this->vote($username, $postid, $answerid, $commentid, $vote);
    }

    public function removeVoteComment($postid, $answerid, $vote, $username , $commentid) {
        $sql = "DELETE FROM answerCommentVotes WHERE id = ? AND username = ? AND answerid = ? AND commentid = ?";
        $res = $this->db->execute($sql, [$postid, $username, $answerid, $commentid]);
    }

    public function updateVoteComment($postid, $answerid, $vote, $username, $commentid)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        $sql = "UPDATE answerCommentVotes SET vote = $voteScore WHERE postid = ? AND username = ? AND commentid = ? AND answerid = ?";

        $this->db->execute($sql, [$postid, $username, $commentid, $answerid]);
    }

    public function commentStatus($postid, $answerid, $username, $commentid)
    {
        $sql = "SELECT comments.*, SUM(answerCommentVotes.vote) as score FROM comments LEFT OUTER JOIN answerCommentVotes ON answerCommentVotes.commentid = comments.id WHERE comments.postid = ? AND comments.answerid = ? AND comments.username = ? AND comments.id = ?";
        $res = $this->db->executeFetch($sql, [$postid, $answerid, $username, $commentid]);

        return $res;
    }
}