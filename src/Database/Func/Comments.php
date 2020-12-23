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
        $sql = "SELECT * FROM comments WHERE username = ? ORDER BY date DESC ";

        return $this->db->executeFetchAll($sql, [$username]);
    }
    
    public function postComments($id) {
        $sql = "SELECT * FROM comments WHERE postid = ? AND commentid = 0";
        
        return $this->db->executeFetchAll($sql, [$id]);
    }
    
    public function commentComments($postid, $commentid) {
        $sql = "SELECT * FROM comments WHERE postid = ? AND commentid = ?";
        
        return $this->db->executeFetchAll($sql, [$id, $commentid]);
    }
}