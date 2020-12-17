<?php
namespace Ida\Database\Func;
use Ida\Database\DB;

class Vote extends DB
{

    public function __construct()
    {
        parent::__construct();
    }

    public function votePost($id, $vote) {
        if ($vote == "upvote") {
            $sql = "UPDATE posts SET upvote = upvote + 1 WHERE id = ?";
            $res = $this->db->execute($sql, [$id]);
            
            $sql = "SELECT upvote FROM posts WHERE id = ?";
        } else {
            $sql = "UPDATE posts SET downvote = downvote + 1 WHERE id = ?";
            $res = $this->db->execute($sql, [$id]);
            $sql = "SELECT downvote FROM posts WHERE id = ?";
        }

        $res = $this->db->executeFetch($sql, [$id]);

        return json_encode($res);
    }
}