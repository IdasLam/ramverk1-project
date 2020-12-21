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

    public function votePost($id, $vote, $username, $commentid = null) {
        $voted = $this->hasvotedPost($username, $id, $commentid);

        if ($voted !== true) {
            if ($vote == "upvote") {
                $sql = "UPDATE posts SET upvote = upvote + 1 WHERE id = ?";
                $res = $this->db->execute($sql, [$id]);
                
                $sql = "SELECT upvote FROM posts WHERE id = ?";
                vote($username, $postid, $commentid, 1);
            } else {
                $sql = "UPDATE posts SET downvote = downvote + 1 WHERE id = ?";
                $res = $this->db->execute($sql, [$id]);
                $sql = "SELECT downvote FROM posts WHERE id = ?";
                vote($username, $postid, $commentid, 0);
            }
    
            $res = $this->db->executeFetch($sql, [$id]);
    
            return json_encode($res);
        } else {
            // unvote
        }
    }
}