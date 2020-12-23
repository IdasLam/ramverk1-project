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

    public function topTags() {
        $sql = "SELECT tag FROM posts";
        $res = $this->db->executeFetchAll($sql);

        $tagCount = [];

        foreach($res as $row) {
            $tags = explode(",", $row->tag);

            foreach ($tags as $tag) {
                if (array_key_exists($tag, $tagCount)) {
                    $tagCount[$tag] += 1;
                } else {
                    $tagCount[$tag] = 1;
                }
            }
        }
        
        arsort($tagCount);
        
        return array_slice($tagCount, 0, 4);
    }
}