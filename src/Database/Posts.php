<?php

namespace Ida\Database;

class Posts extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function latestPosts()
    {
        $sql = "SELECT * FROM posts ORDER BY date DESC LIMIT 0, 10";

        return $this->db->executeFetchAll($sql);
    }
}