-- CREATE DATABASE IF NOT EXISTS idla18;
-- USE idla18;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS comments;

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
    upvote INT DEFAULT 0,
    downvote INT DEFAULT 0,
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
    upvote INT DEFAULT 0,
    downvote INT DEFAULT 0,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
);

CREATE TABLE vote
(
    id INTEGER PRIMARY KEY,

    postid INT,
    commentid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    vote BIT DEFAULT 0,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
);



INSERT INTO users(username, password, email) VALUES("admin", 'admin', 'admin@admin.com');

INSERT INTO posts(username, content, tag) VALUES("admin", "#first post", "admin");

