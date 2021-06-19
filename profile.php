<?php
require('init.php');
require('helpers.php');
require('db.php');
require('utils.php');
require('validation-func.php');

init_check_auth('/');

$con = get_db_connection();
$title = 'readme: профиль';

$user_id = !empty($_GET) && !empty($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['id'];
$user_info = get_info_about_post_author($con, $user_id);
$user_posts = get_user_posts($con, $user_id);
$is_your_profile = $user_id == $_SESSION['id'];
$is_subscription = $is_your_profile ? false : !empty(check_subscription($con, $user_id, $_SESSION['id']));
$error_post_comment = 0;
$active_tab = !empty($_GET) && !empty($_GET['tab']) ? $_GET['tab'] : 'posts';
$likes_list = get_likes_list($con, $user_id);

function post_hashtags($post_id) {
    global $con;
    return get_post_hashtags($con, $post_id);
};

function post_comments($post_id) {
    global $con;
    global $error_post_comment;
    $comments = get_comments_for_post($con, $post_id);

    if ($error_post_comment > 0) {
        return [
            'length' => count($comments),
            'comments' => $comments
        ];
    }

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

function check_is_liked_post($post_id) {
    global $con;
    return !empty(check_like($con, $_SESSION['id'], $post_id)) ? 'icon-heart-active' : 'icon-heart';
};

$form_validations = [
    'message' => [
        0 => function ($name) {
            return validate_filled($name);
        },
        1 => function ($name) {
            return validate_length($name, 4);
        }
    ],
];

$error_field_titles = [
    'message' => 'Комментарий'
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST &&
        !empty($_POST['add_comment']) &&
        !empty($_POST['post']) &&
        !empty($_POST['user'])
    ) {
        $post_id = $_POST['post'];
        $validation_result = validation_validate($form_validations, $error_field_titles);
        $errors = $validation_result['errors'];
        $values = $validation_result['values'];

        if (empty($errors)) {
            send_comment($con, $_POST);

            header("Location: /profile.php?user_id=$user_id&post_id=$post_id&show_comments&all_comments&tab=posts");
        } else {
            $error_post_comment = $post_id;
        }
    }
}

$page_content = include_template('profile/profile.php', [
    'user_info' => $user_info,
    'user_posts' => $user_posts,
    'actual_user_avatar' => $_SESSION['avatar'],
    'actual_user_id' => $_SESSION['id'],
    'user_id' => $user_id,
    'is_your_profile' => $is_your_profile,
    'is_subscription' => $is_subscription,
    'errors' => $errors,
    'error_post_comment' => $error_post_comment,
    'active_tab' => $active_tab,
    'likes_list' => $likes_list
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar'],
    'title' => $title
]);
print($page);
