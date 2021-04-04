CREATE DATABASE readme
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    login VARCHAR(128),
    password CHAR(64) NOT NULL,
    avatar TEXT
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title TEXT,
    content TEXT,
    AUTHOR VARCHAR(128),
    image TEXT,
    video TEXT,
    link TEXT,
    shown_count INT(11),
    author_id INT(11),
    type_content_id INT(11),
    hashtags INT(11)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    message TEXT,
    author_id INT(11),
    post_id INT(11)
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    post_id INT(11)
);

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    follower_id INT(11)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content TEXT,
    author_id INT(11),
    recipient_id INT(11)
);

CREATE TABLE hashtags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(128) UNIQUE
);

CREATE TABLE content_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) UNIQUE,
    class_name VARCHAR(128) UNIQUE
);
