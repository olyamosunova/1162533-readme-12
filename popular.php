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
$sort_type = !empty($_GET) && !empty($_GET['sort_type']) ? $_GET['sort_type'] : 'popular';
$sort_direction = !empty($_GET) && !empty($_GET['sort_direction']) ? $_GET['sort_direction'] : 'DESC';
$popular_posts = get_popular_posts(
    $con,
    $active_type_content_id,
    $sort_type,
    $sort_direction,
    POPULAR_POSTS_LIMIT,
    $offset);
$popular_posts_count = get_popular_posts_count($con, $active_type_content_id)['count'];

function get_sort_link($sort) {
    global $active_type_content_id;
    global $sort_type;
    global $sort_direction;

    $direction = $sort_type == $sort ? ($sort_direction === 'ASC' ? 'DESC' : 'ASC') : $sort_direction;

    return "popular.php?content_id=$active_type_content_id&sort_type=$sort&sort_direction=$direction";
};

$page_content = include_template('main.php', [
    'popular_posts' => $popular_posts,
    'popular_posts_count' => $popular_posts_count,
    'content_types' => $content_types,
    'active_type_content_id' => $active_type_content_id,
    'limit' => POPULAR_POSTS_LIMIT,
    'page' => $page,
    'sort_type' => $sort_type,
    'sort_direction' => $sort_direction,
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
