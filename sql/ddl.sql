-- CREATE DATABASE IF NOT EXISTS idla18;
-- USE idla18;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS votes;

CREATE TABLE users
(
    id INTEGER PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE posts
(
    id INTEGER PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    -- upvote INT DEFAULT 0,
    -- downvote INT DEFAULT 0,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    tag TEXT,

    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE comments
(
    id INTEGER PRIMARY KEY,

    postid INT,
    commentid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    -- upvote INT DEFAULT 0,
    -- downvote INT DEFAULT 0,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
);

CREATE TABLE votes
(
    id INTEGER PRIMARY KEY,

    postid INT DEFAULT NULL,
    commentid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    vote INT NOT NULL,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
);



INSERT INTO users(username, password, email) VALUES("admin", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'admin@admin.com');

INSERT INTO posts(username, content, tag) VALUES("admin", "#first post", "admin");

