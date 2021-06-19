<?php
require('init.php');
require('helpers.php');
require('db.php');
require('utils.php');

init_check_auth('/');

$con = get_db_connection();
$title = 'readme: профиль';

$user_id = !empty($_GET) && !empty($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['id'];
$user_info = get_info_about_post_author($con, $user_id);
$user_posts = get_user_posts($con, $user_id);
$is_your_profile = $user_id == $_SESSION['id'];
$is_subscription = $is_your_profile ? false : !empty(check_subscription($con, $user_id, $_SESSION['id']));

function post_hashtags($post_id) {
    global $con;
    return get_post_hashtags($con, $post_id);
};

function post_comments($post_id) {
    global $con;
    $comments = get_comments_for_post($con, $post_id);
    if (!empty($_GET) &&
        !empty($_GET['post_id']) &&
        $_GET['post_id'] == $post_id &&
        isset($_GET['all_comments'])) {
        return [
            'length' => count($comments),
            'comments' => $comments
        ];
    }

    return [
        'length' => count($comments),
        'comments' => array_slice($comments, 0, 2)
    ];
};

function check_show_comments($post_id) {
    if (!empty($_GET) &&
        !empty($_GET['post_id']) &&
        $_GET['post_id'] == $post_id &&
        isset($_GET['show_comments'])) {
        return true;
    }

    return false;
};

$page_content = include_template('profile/profile.php', [
    'user_info' => $user_info,
    'user_posts' => $user_posts,
    'actual_user_avatar' => $_SESSION['avatar'],
    'actual_user_id' => $_SESSION['id'],
    'user_id' => $user_id,
    'is_your_profile' => $is_your_profile,
    'is_subscription' => $is_subscription
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar'],
    'title' => $title
]);
print($page);
