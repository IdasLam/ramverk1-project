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
    
    public function postAnswers($id, $answerid) {
        if (isset($answerid)) {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? AND answers.id = ?";
            $res = $this->db->executeFetch($sql, [$id, $answerid]);
            
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? AND NOT answers.id = ? GROUP BY answers.id";
            
            
            $answers = $this->db->executeFetchAll($sql, [$id, $answerid]);
            
            array_unshift($answers, $res);
        } else {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? GROUP BY answers.id";
            $answers = $this->db->executeFetchAll($sql, [$id]);

        }

        return $answers;
    }

    public function dateAnswers($id, $order) {
        if ($order === "DESC") {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? GROUP BY answers.id ORDER BY answers.date DESC";
            
        } else {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? GROUP BY answers.id ORDER BY answers.date ASC";
            
        }

        return $this->db->executeFetchAll($sql, [$id]);
    }
    
    
    public function orderUpvotesAnswers($id, $order) {
        if ($order === "DESC") {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? GROUP BY answers.id ORDER BY score DESC";
        } else {
            $sql = "SELECT answers.*, COALESCE(SUM(answerVotes.vote), 0) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? GROUP BY answers.id ORDER BY score ASC"; 
        }

        return $this->db->executeFetchAll($sql, [$id]);
    }
    
    public function postComments($id, $answerid) {
        // $sql = "SELECT * FROM comments WHERE postid = ? AND answerid = ?";
        $sql = "SELECT comments.*, COALESCE(SUM(commentVotes.vote), 0) as score FROM comments LEFT OUTER JOIN commentVotes ON commentVotes.commentid = comments.id WHERE comments.postid = ? AND comments.answerid = ? GROUP BY comments.id ORDER BY score DESC";
        
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