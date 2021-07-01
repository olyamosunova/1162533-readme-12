<?php
/* @var Closure $utils_url_to */
/* @var Closure $check_is_liked_post */
/* @var Closure $check_subs */

require('init.php');
require('helpers.php');
require('utils.php');
require('db.php');
require('validation-func.php');

init_check_auth('/');

$validations = [
    'ID' => [
        function ($name) {
            $value = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT);

            if (!$value) {
                $value = NULL;
            }
            return validation_result($value);
        }
    ]
];

$validation_result = validation_validate($validations);
$values = $validation_result['values'];

$con = get_db_connection();
$user = init_get_user();
$title = 'readme: пост';
$post_id = $values['ID'];

if (!$post_id) {
    not_found_page($user['user_name']);
}

$post = get_post($con, $post_id);

if (!$post) {
    not_found_page($user['user_name']);
}

$author_info = get_info_about_post_author($con, $post['user_id']);
$hashtags = get_post_hashtags($con, $post_id);
$comments = get_comments_for_post($con, $post_id);

$check_is_liked_post = function($post_id) use ($con, $user)
{
    return check_liked_post($con, $post_id, $user);
};

$check_subs = function ($user_id) use ($con, $user)
{
    return !empty(check_subscription($con, $user_id, $user['id']));
};

$page_content = include_template('post-details.php', [
    'post' => $post,
    'author_info' => $author_info,
    'hashtags' => $hashtags,
    'comments' => $comments,
    'to' => $utils_url_to,
    'user' => $user,
    'check_is_liked_post' => $check_is_liked_post,
    'check_subs' => $check_subs
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'user' => $user,
    'title' => $title
]);
print($page);
