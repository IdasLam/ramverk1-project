-- CREATE DATABASE IF NOT EXISTS idla18;
-- USE idla18;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS votes;
DROP TABLE IF EXISTS answerVotes;
DROP TABLE IF EXISTS commentVotes;

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

CREATE TABLE answers
(
    id INTEGER PRIMARY KEY,

    postid INT,
    username VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,

    date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE comments
(
    id INTEGER PRIMARY KEY,

    postid INT,
    answerid INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,

    date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (answerid) REFERENCES answers(answerid)
);

CREATE TABLE votes
(
    id INTEGER PRIMARY KEY,

    postid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    vote INT NOT NULL,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE answerVotes
(
    id INTEGER PRIMARY KEY,

    postid INT DEFAULT NULL,
    answerid INT DEFAULT NULL,
    -- commentid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    vote INT NOT NULL,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE commentVotes
(
    id INTEGER PRIMARY KEY,

    postid INT DEFAULT NULL,
    answerid INT DEFAULT NULL,
    commentid INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    vote INT NOT NULL,

    FOREIGN KEY (postid) REFERENCES posts(postid),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
);



INSERT INTO users(username, password, email) VALUES("test", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'test@test.com');
INSERT INTO users(username, password, email) VALUES("admin", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'admin@admin.com');
INSERT INTO users(username, password, email) VALUES("ida", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'asd@admin.com');
INSERT INTO users(username, password, email) VALUES("Stinky", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'asdasd@admin.com');
INSERT INTO users(username, password, email) VALUES("stonl", '$2y$10$mIp8cfJ/pCw72pE6uDjlaen8zWUyIl5XzhxT/SWBDIRxZeL3EnT16', 'asdasdas@admin.com');

INSERT INTO posts(username, content, tag) VALUES("admin", "#first post", "admin");
INSERT INTO posts(username, content, tag) VALUES("admin", "#sec post", "admin,test");

INSERT INTO answers(postid, username, content) VALUES(1, "admin", "hello");
INSERT INTO answers(postid, username, content) VALUES(1, "test", "test");

INSERT INTO comments(postid, username, content, answerid) VALUES(1, "test", "hello admin", 1);
INSERT INTO comments(postid, username, content, answerid) VALUES(1, "test", "hello test admin", 1);


