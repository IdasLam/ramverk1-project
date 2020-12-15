SET NAMES utf8;

--
-- Create table for my database
--
CREATE DATABASE IF NOT EXISTS idla18;
USE idla18;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `posts`;

CREATE TABLE `users`
(
    `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
)

CREATE TABLE `posts`
(
    `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `upvote` INT DEFAULT 0,
    `downvote` INT DEFAULT 0,
    `tag` TEXT,

    FOREIGN KEY (username) REFERENCES users(username)
)

CREATE TABLE `comments`
(
    `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,

    `postid` INT,
    `commentid` INT DEFAULT NULL,
    `username` VARCHAR(255) NOT NULL,
    `upvote` INT DEFAULT 0,
    `downvote` INT DEFAULT 0,

    FOREIGN KEY (postid) REFERENCES posts(postid)
    FOREIGN KEY (username) REFERENCES users(username)
    FOREIGN KEY (commentid) REFERENCES comments(commentid)
)

