<?php
namespace Ida\Database\Func;
use Ida\Database\DB;

class Comments extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function profileComments($username) {
        $sql = "SELECT * FROM comments WHERE username = ? ORDER BY date DESC";

        return $this->db->executeFetchAll($sql, [$username]);
    }
    
    public function profileAnswers($username) {
        $sql = "SELECT * FROM answers WHERE username = ? ORDER BY date DESC";

        return $this->db->executeFetchAll($sql, [$username]);
    }
    
    public function postAnswers($id) {
        $sql = "SELECT answers.*, SUM(answerCommentVotes.vote) as score FROM answers LEFT OUTER JOIN answerCommentVotes ON answerCommentVotes.answerid = answers.id WHERE answers.postid = ?";

        // $sql = "SELECT * FROM answers WHERE postid = ?";
        
        return $this->db->executeFetchAll($sql, [$id]);
    }
    
    public function postComments($id, $answerid) {
        // $sql = "SELECT * FROM comments WHERE postid = ? AND answerid = ?";
        $sql = "SELECT comments.*, SUM(answerCommentVotes.vote) as score FROM comments LEFT OUTER JOIN answerCommentVotes ON answerCommentVotes.commentid = comments.id WHERE comments.postid = ? AND comments.answerid = ? GROUP BY comments.id ORDER BY date DESC";
        
        return $this->db->executeFetchAll($sql, [$id, $answerid]);
    }
    
    public function commentComments($postid, $answerid) {
        $sql = "SELECT * FROM comments WHERE postid = ? AND answerid = ?";
        
        return $this->db->executeFetchAll($sql, [$id, $answerid]);
    }

    public function newComment($content, $postid, $answerid, $username) {
        $sql = "INSERT INTO comments (content, postid, answerid, username) VALUES (?, ?, ?, ?)";

        return $this->db->execute($sql, [$content, $postid, $answerid, $username]);
    }
    
    public function newAnswer($content, $postid, $username) {
        $sql = "INSERT INTO answers (content, postid, username) VALUES (?, ?, ?)";

        return $this->db->execute($sql, [$content, $postid, $username]);
    }
}