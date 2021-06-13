<?php
require('init.php');
require('helpers.php');
require('utils.php');
require('db.php');

init_check_auth('/');

$con = get_db_connection();
$title = 'readme: Страница результатов поиска';

$search_query_text = !empty($_GET['search']) ? htmlspecialchars($_GET['search']) : null;
$founded_posts = [];

if ($search_query_text) {
    $value_query = trim($search_query_text);

    if (substr($value_query, 0, 1) === "#") {
        $value_query = substr($value_query, 1, strlen($value_query));
        $founded_posts = get_search_hashtag_results($con, $value_query);
    } else {
        $founded_posts = get_search_results($con, $value_query);
    }
}

$page_content = include_template('search-results.php', [
    'search_query_text' => $search_query_text,
    'founded_posts' => $founded_posts
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => $title,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar'],
    'search_query_text' => $search_query_text
]);

print($page);
