<?php
require('init.php');
require('helpers.php');
require('utils.php');
require('validation-func.php');
require('db.php');

init_check_auth('/');

$con = get_db_connection();
$page_name = 'popular';
$title = 'readme: популярное';
define("POPULAR_POSTS_LIMIT", 9);
$content_types = get_post_content_types($con);
$active_type_content_id = filter_input(INPUT_GET, 'content_id');
$page = !empty($_GET) && !empty($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * POPULAR_POSTS_LIMIT;
$popular_posts = get_popular_posts($con, $active_type_content_id, POPULAR_POSTS_LIMIT, $offset);
$popular_posts_count = get_popular_posts_count($con, $active_type_content_id)['count'];

$page_content = include_template('main.php', [
    'popular_posts' => $popular_posts,
    'popular_posts_count' => $popular_posts_count,
    'content_types' => $content_types,
    'active_type_content_id' => $active_type_content_id,
    'limit' => POPULAR_POSTS_LIMIT,
    'page' => $page
]);
$page = include_template('layout.php', [
    'page_content' => $page_content,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar'],
    'title' => $title,
    'page_name' => $page_name
]);
print($page);
