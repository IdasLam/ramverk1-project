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
        $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT OUTER JOIN votes ON votes.postid = posts.id GROUP BY posts.id ORDER BY date DESC LIMIT 0, 2";

        return $this->db->executeFetchAll($sql);
    }
    
    function allLatestPosts()
    {
        $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT OUTER JOIN votes ON votes.postid = posts.id GROUP BY posts.id ORDER BY date DESC";

        return $this->db->executeFetchAll($sql);
    }
    
    public function newPosts($tag, $content, $username)
    {
        $sql = "INSERT INTO posts (tag, content, username) VALUES (?, ?, ?)";

        return $this->db->execute($sql, [$tag, $content, $username]);
    }

    public function fetchPost($id) {
        $sql = "SELECT posts.*, SUM(votes.vote) as score FROM posts LEFT OUTER JOIN votes ON votes.postid = posts.id WHERE posts.id = ? GROUP BY posts.id LIMIT 1";

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

    public function profilePost($username) {
        $sql = "SELECT * FROM posts WHERE username = ? ORDER BY date DESC ";

        return $this->db->executeFetchAll($sql, [$username]);
    }
}