<?php
require('helpers.php');
require('utils.php');
require('db.php');

$con = get_db_connection();
$user_name = 'Olya';
$title = 'readme: популярное';
$post_id = filter_input(INPUT_GET, 'ID');

// Проверяет наличие параметра запроса;
if (!$post_id) {
    not_found_page($user_name);
}

$post = get_post($con, $post_id);

if (!$post) {
    not_found_page($user_name);
}

$author_info = get_info_about_post_author($con, $post['user_id']);
$hashtags = get_post_hashtags($con, $post_id);
$comments = get_comments_for_post($con, $post_id);

$page_content = include_template('post-details.php', [
    'post' => $post,
    'author_info' => $author_info,
    'hashtags' => $hashtags,
    'comments' => $comments
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'user_name' => $user_name,
    'title' => $title
]);
print($page);
