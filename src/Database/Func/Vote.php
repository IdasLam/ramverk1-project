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
        $sql = "INSERT INTO vote (username, postid, commentid, vote) VALUES (?, ?, ?, ?)";
        $res = $this->db->execute($sql, [$username, $postid, $commentid, $vote]);
    }
    
    public function hasvotedPost($username, $postid, $commentid)
    {
        $sql = "SELECT * FROM vote WHERE postid LIKE ? AND $username LIKE ? AND commentid LIKE NULL";
        $res = $this->db->executeFetch($sql, [$username, $postid, $commentid]);

        return count($res) > 0;
    }

    public function votePost($id, $vote, $username) {
        if ($vote == "upvote") {
            $this->vote($username, $id, null, 1);
            $sql = "UPDATE posts SET upvote = upvote + 1 WHERE id = ?";
            $res = $this->db->execute($sql, [$id]);
            
            $sql = "SELECT upvote FROM posts WHERE id = ?";
        } else {
            $this->vote($username, $id, null, 0);
            $sql = "UPDATE posts SET downvote = downvote + 1 WHERE id = ?";
            $res = $this->db->execute($sql, [$id]);
            $sql = "SELECT downvote FROM posts WHERE id = ?";
        }

        $res = $this->db->executeFetch($sql, [$id]);

        return json_encode($res);
    }

    public function removeVotePost($postid, $vote, $username) {
        $sql = "DELETE FROM posts WHERE id = ? AND username = ? AND commentid = null";
        $res = $this->db->execute($sql, [$postid, $username]);
        
        $sql = "DELETE FROM vote WHERE id = ? AND username = ? AND commentid = null";
        $res = $this->db->execute($sql, [$postid, $username]);
        
        if ($vote == "upvote") {
            $sql = "SELECT upvote FROM posts WHERE id = ?";
        } else {
            $sql = "SELECT downvote FROM posts WHERE id = ?";
        }

        $res = $this->db->executeFetch($sql, [$id]);

        var_dump($res);

        return json_encode($res);
    }
}