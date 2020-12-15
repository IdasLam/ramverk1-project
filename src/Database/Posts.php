<?php

namespace Ida\Database;

class Posts extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function allPosts()
    {
        $sql = "SELECT * FROM posts";

        return $this->db->executeFetchAll($sql);
    }
}