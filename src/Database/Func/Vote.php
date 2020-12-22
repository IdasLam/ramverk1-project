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
        // if ($vote === 1) {
        //     $this->vote($username, $id, null, 1);
        //     $sql = "UPDATE posts SET upvote = upvote + 1 WHERE id = ?";
        //     $res = $this->db->execute($sql, [$id]);
            
        //     $sql = "SELECT upvote FROM posts WHERE id = ?";
        // } else {
        //     $this->vote($username, $id, null, 0);
        //     $sql = "UPDATE posts SET downvote = downvote + 1 WHERE id = ?";
        //     $res = $this->db->execute($sql, [$id]);
        //     $sql = "SELECT downvote FROM posts WHERE id = ?";
        // }

        // $res = $this->db->executeFetch($sql, [$id]);

        // return json_encode($res);

        $this->vote($username, $postid, null, $vote);
    }

    public function removeVotePost($postid, $vote, $username) {
        $sql = "DELETE FROM votes WHERE id = ? AND username = ? AND commentid IS NULL";
        $res = $this->db->execute($sql, [$postid, $username]);
    }

    public function voteStatusPost($postid, $vote)
    {
        if ($vote === 1) {
            $sql = "SELECT upvote FROM posts WHERE id = ?";
        } else {
            $sql = "SELECT downvote FROM posts WHERE id = ?";
        }

        $res = $this->db->executeFetch($sql, [$postid]);

        return json_encode($res);
    }
    
    public function updateVotePost($postid, $vote, $username)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        ([$postid, $vote, $username, $voteScore]);
        $sql = "UPDATE votes SET vote = $voteScore WHERE postid = ? AND username = ? AND commentid IS NULL";
        // $sql = "UPDATE vote SET vote = $voteScore WHERE id = ? AND username = ? AND commentid = 0";

        $this->db->execute($sql, [$postid, $username]);

        // $this->votePost($postid, $vote, $username);
    }
}