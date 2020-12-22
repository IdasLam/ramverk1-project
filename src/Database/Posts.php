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
        $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT JOIN votes ORDER BY date DESC LIMIT 0, 10";

        return $this->db->executeFetchAll($sql);
    }

    public function fetchPost($id) {
        $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT JOIN votes WHERE posts.id = ? LIMIT 1";

        return $this->db->executeFetch($sql, [$id]);
    }
}