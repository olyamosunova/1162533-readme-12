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
    p.date_add
FROM post p
JOIN user u ON p.user_id = u.id
JOIN content_type c ON p.content_type_id =  c.id
WHERE
$active_type_content_id > 1 AND p.content_type_id = $active_type_content_id
OR
$active_type_content_id = 1 AND p.content_type_id >= $active_type_content_id
ORDER BY p.shown_count DESC
LIMIT 6;";

    $result_popular_post = mysqli_query($con, $sql_post_popular);
    $popular_posts = [];

    if ($result_popular_post) {
        $popular_posts = mysqli_fetch_all($result_popular_post, MYSQLI_ASSOC);
    }

    return $popular_posts;
}
