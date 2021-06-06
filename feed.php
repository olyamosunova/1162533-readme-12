<?php
require('init.php');
require('helpers.php');
require('utils.php');
require('db.php');

init_check_auth('/');

$page_name = 'feed';

$con = get_db_connection();
$title = 'Readme: моя лента';
$content_types = get_post_content_types($con);
$active_type_content_id = filter_input(INPUT_GET, 'ID') ?? 1;
$posts = get_posts_for_me($con, $active_type_content_id, $_SESSION['id']);

$page_content = include_template('feed.php', [
    'active_type_content_id' => $active_type_content_id,
    'content_types' => $content_types,
    'posts' => $posts
]);

$page = include_template('layout.php', [
    'title' => $title,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar'],
    'page_content' => $page_content,
    'page_name' => $page_name
]);

print($page);
