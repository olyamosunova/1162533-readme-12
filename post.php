<?php
require('helpers.php');
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

function format_register_date($date) {
    date_default_timezone_set('Europe/Moscow');
    $cur_date = date_create("now");
    $diff = date_diff($cur_date, date_create($date));
    $minutes = date_interval_format($diff, "%i");
    $hours = date_interval_format($diff, "%h");
    $days = date_interval_format($diff, "%d");
    $months = date_interval_format($diff, "%m");
    $years = date_interval_format($diff, "%y");

    if ($years > 0) {
        return $years . ' ' .
            get_noun_plural_form($years, 'год', 'года', 'лет') . ' на сайте';
    }
    if ($months > 0 && $months < 12) {
        return $months . ' ' .
            get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' на сайте';
    } elseif ($days > 6) {
        return floor($days / 7) . ' ' .
            get_noun_plural_form(floor(($days / 7)), ' неделю', ' недели', ' недель') . ' на сайте';
    } elseif ($days > 0) {
        return $days . ' ' .
            get_noun_plural_form($days, 'день', 'дня', 'дней') . ' на сайте';
    } elseif ($hours > 0) {
        return $hours . ' ' .
            get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' на сайте';
    } elseif ($minutes > 0) {
        return $minutes . ' ' .
            get_noun_plural_form($minutes, 'минуту', 'минуты', 'минут') . ' на сайте';
    } elseif ($minutes <= 0) {
        return 'новый пользователь';
    } else {
        return '';
    }
};

function get_text_count_followers($count) {
    return get_noun_plural_form($count, 'подписчик', 'подписчика', 'подписчиков');
}

function get_text_count_publications($count) {
    return get_noun_plural_form($count, 'публикация', 'публикации', 'публикаций');
}

function get_text_count_shown($count) {
    return $count . " " . get_noun_plural_form($count, 'просмотр', 'просмотра', 'просмотров');
}

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
