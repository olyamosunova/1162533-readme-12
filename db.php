<?php

define('MYSQL_HOST', '127.0.0.1:3306');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', 'root');
define('MYSQL_DB_NAME', 'readme');

/**
 * Подключение к базе данных
 * @return mysqli
 */

function get_db_connection() {
    $con = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD,MYSQL_DB_NAME);
    mysqli_set_charset($con, "utf8");

    if ($con == false) {
        print("Ошибка подключения: " . mysqli_connect_error());
        die();
    }

    return $con;
}

/**
 * Получение списка типов контента
 * @param mysqli $con
 * @return array
 */
function get_post_content_types($con) {
    $sql_content_type = "SELECT * FROM content_type";
    $result_content_type = mysqli_query($con, $sql_content_type);
    $content_types = [];

    if ($result_content_type) {
        $content_types = mysqli_fetch_all($result_content_type, MYSQLI_ASSOC);
    }

    return $content_types;
}

/**
 * Получение списка популярных постов
 * @param mysqli $con
 * @param number $active_type_content_id
 * @return array
 */
function get_popular_posts($con, $active_type_content_id) {
    $active_type_content_id = $active_type_content_id ? $active_type_content_id : 1;

    $sql_post_popular = "
SELECT
    p.id,
    p.title,
    p.content,
    p.author,
    u.user_name,
    u.avatar,
    p.shown_count,
    u.login,
    c.class_name as type,
    p.date_add,
    (SELECT COUNT(1) FROM likes WHERE likes.post_id = p.id) AS likes_count,
    (SELECT COUNT(1) FROM comment WHERE comment.post_id = p.id) AS comments_count
FROM post p
JOIN user u ON p.user_id = u.id
JOIN content_type c ON p.content_type_id =  c.id
WHERE
? > 1 AND p.content_type_id = ?
OR
? = 1 AND p.content_type_id >= ?
ORDER BY p.shown_count DESC
LIMIT 6;";

    $popular_posts = [];
    $stmt = db_get_prepare_stmt(
        $con,
        $sql_post_popular,
        [$active_type_content_id, $active_type_content_id, $active_type_content_id, $active_type_content_id]);
    mysqli_stmt_execute($stmt);
    $result_popular_post = mysqli_stmt_get_result($stmt);

    if ($result_popular_post) {
        $popular_posts = mysqli_fetch_all($result_popular_post, MYSQLI_ASSOC);
    }

    return $popular_posts;
}

function get_post($con, $post_id) {
    $sql_post = "
SELECT
    p.id,
    p.date_add,
    p.title,
    p.content,
    p.author,
    p.shown_count,
    p.user_id,
    c.class_name AS content_type_name,
    (SELECT COUNT(1) FROM likes WHERE likes.post_id = p.id) AS likes_count,
    (SELECT COUNT(1) FROM comment WHERE comment.post_id = p.id) AS comments_count
FROM post p
JOIN content_type c ON p.content_type_id = c.id
WHERE p.id = ?";

    $stmt = db_get_prepare_stmt(
        $con,
        $sql_post,
        [$post_id]);
    mysqli_stmt_execute($stmt);
    $result_post = mysqli_stmt_get_result($stmt);
    $post = null;

    if ($result_post) {
        $post = mysqli_fetch_all($result_post, MYSQLI_ASSOC);
    }

    return $post ? $post[0] : $post;
}

function get_post_hashtags($con, $post_id) {
    $sql_hashtags = "SELECT ph.hashtag_id, h.title FROM PostHashtag ph JOIN hashtag h ON ph.hashtag_id = h.id WHERE ph.post_id = ?";
    $stmt = db_get_prepare_stmt(
        $con,
        $sql_hashtags,
        [$post_id]);
    mysqli_stmt_execute($stmt);
    $result_hashtags = mysqli_stmt_get_result($stmt);
    $hashtags = null;

    if ($result_hashtags) {
        $hashtags = mysqli_fetch_all($result_hashtags, MYSQLI_ASSOC);
    }

    return $hashtags;
}

function get_info_about_post_author($con, $author_id) {
    $sql_author_info = "
SELECT
    u.id,
    u.user_name,
    u.avatar,
    u.date_add,
    (SELECT COUNT(1) FROM subscription WHERE subscription.user_id = u.id) AS count_followers,
    (SELECT COUNT(1) FROM post WHERE post.user_id = u.id) AS count_posts
FROM user u
WHERE u.id = ?
GROUP BY u.id";
    $stmt = db_get_prepare_stmt(
        $con,
        $sql_author_info,
        [$author_id]);
    mysqli_stmt_execute($stmt);
    $result_author_info = mysqli_stmt_get_result($stmt);
    $author = null;

    if ($result_author_info) {
        $author = mysqli_fetch_all($result_author_info, MYSQLI_ASSOC);
    }

    return $author[0];
}

function get_comments_for_post($con, $post_id) {
    $sql_comments = "
SELECT
    c.id,
    c.date_add,
    c.message,
    u.user_name AS author_name,
    u.avatar AS author_avatar
FROM comment c
JOIN user u ON c.user_id = u.id
WHERE c.post_id = ?";
    $stmt = db_get_prepare_stmt(
        $con,
        $sql_comments,
        [$post_id]);
    mysqli_stmt_execute($stmt);
    $result_comments = mysqli_stmt_get_result($stmt);
    $comments = null;

    if ($result_comments) {
        $comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);
    }

    return $comments;
}
