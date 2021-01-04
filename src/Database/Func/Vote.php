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
            $sql = "INSERT INTO answerVotes (username, postid, answerid, vote) VALUES ( ?, ?, ?, ?)";
            $res = $this->db->execute($sql, [$username, intval($postid), $answerid, $vote > 0 ? 1 : -1]);
        } elseif (isset($commentid)) {
            $sql = "INSERT INTO commentVotes (username, commentid, postid, answerid, vote) VALUES (?, ?, ?, ?, ?)";
            $res = $this->db->execute($sql, [$username, $commentid, $answerid, intval($postid), $vote > 0 ? 1 : -1]);
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
        $sql = "SELECT * FROM answerVotes WHERE postid = ? AND username = ? AND answerid = ?";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid]);

        return $res != null ? $res->vote : null;
    }

    public function voteAnswer($postid, $answerid, $vote, $username) {
        $this->vote($username, $postid, $answerid, null, $vote);
    }

    public function removeVoteAnswer($postid, $answerid, $vote, $username) {
        $sql = "DELETE FROM answerVotes WHERE id = ? AND username = ? AND answerid = ?";
        $res = $this->db->execute($sql, [$postid, $username, $answerid]);
    }

    public function updateVoteAnswer($postid, $answerid, $vote, $username)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        $sql = "UPDATE answerVotes SET vote = $voteScore WHERE postid = ? AND username = ? AND answerid = ?";

        $this->db->execute($sql, [$postid, $username, $answerid]);
    }

    public function answerStatus($postid, $answerid, $username)
    {
        $sql = "SELECT answers.*, SUM(answerVotes.vote) as score FROM answers LEFT OUTER JOIN answerVotes ON answerVotes.answerid = answers.id WHERE answers.postid = ? AND answers.id = ? AND answers.username = ?";
        $res = $this->db->executeFetch($sql, [$postid, $answerid, $username]);

        return $res;
    }
    
    public function hasVotedCommentPost($username, $postid, $answerid, $commentid)
    {
        $sql = "SELECT * FROM commentVotes WHERE postid = ? AND username = ? AND answerid = ? AND commentid = ?";
        $res = $this->db->executeFetch($sql, [$postid, $username, $answerid, $commentid]);

        return $res != null ? $res->vote : null;
    }

    public function voteComment($postid, $answerid, $vote, $username, $commentid) {
        $this->vote($username, $postid, $answerid, $commentid, $vote);
    }

    public function removeVoteComment($postid, $answerid, $vote, $username , $commentid) {
        $sql = "DELETE FROM commentVotes WHERE id = ? AND username = ? AND answerid = ? AND commentid = ?";
        $res = $this->db->execute($sql, [$postid, $username, $answerid, $commentid]);
    }

    public function updateVoteComment($postid, $answerid, $vote, $username, $commentid)
    {
        $voteScore = $vote > 0 ? 1 : -1;
        $sql = "UPDATE commentVotes SET vote = $voteScore WHERE postid = ? AND username = ? AND commentid = ? AND answerid = ?";

        $this->db->execute($sql, [$postid, $username, $commentid, $answerid]);
    }

    public function commentStatus($postid, $answerid, $username, $commentid)
    {
        $sql = "SELECT comments.*, SUM(commentVotes.vote) as score FROM comments LEFT OUTER JOIN commentVotes ON commentVotes.commentid = comments.id WHERE comments.postid = ? AND comments.answerid = ? AND comments.username = ? AND comments.id = ?";
        $res = $this->db->executeFetch($sql, [$postid, $answerid, $username, $commentid]);

        return $res;
    }

    public function profilePoints($username)
    {
        $postPoints = 0;
        $votePoints = 0;
        // points for question, comment, answer
        // points for total question votes
        // points for total comment votes
        // points for total answer votes

        $sql = "SELECT * FROM posts WHERE username = ?";
        $posts = $this->db->executeFetchAll($sql, [$username]);
        $postPoints += count($posts);

        foreach ($posts as $post) {
            $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT OUTER JOIN votes ON votes.postid = posts.id WHERE votes.postid = ?";

            $votePoints += ($this->db->executeFetch($sql, [$post->id]))->score;
        }

        $sql = "SELECT * FROM answers WHERE username = ?";
        $answers = $this->db->executeFetchAll($sql, [$username]);
        $postPoints += count($answers);

        foreach ($answers as $answer) {
            $score = $this->answerStatus($answer->postid, $answer->id, $username);
            $votePoints += $score->score;
        }
        
        $sql = "SELECT * FROM comments WHERE username = ?";
        $comments = $this->db->executeFetchAll($sql, [$username]);
        $postPoints += count($comments);

        foreach ($comments as $comment) {
            $score = $this->commentStatus($comment->postid, $comment->answerid, $username, $comment->id);
            $votePoints += $score->score;
        }

        return $postPoints + $votePoints;
    }
}