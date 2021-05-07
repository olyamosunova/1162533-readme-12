<?php
require('helpers.php');
require('utils.php');
require('db.php');

define("SPACE_SYMBOL_COUNT", 1);
define("ELLIPSIS_SYMBOL_COUNT", 3);

function cut_text($text, $count_symbols = 300) {
    $word_list = explode(" ", $text);
    $symbols_sum = 0;
    $new_word_list = null;

    if (mb_strlen($text, 'utf-8') <= $count_symbols) {
        return '<p>' . $text . '</p>';
    }

    foreach ($word_list as $word) {
        $symbols_sum += mb_strlen($word, 'utf-8') + SPACE_SYMBOL_COUNT;

        if ($symbols_sum + ELLIPSIS_SYMBOL_COUNT >= $count_symbols) {
            $new_word_list[] = '...';
            break;
        }

        $new_word_list[] = $word;
    }

    return '<p>' . implode(' ', $new_word_list) . '</p>' . '<a class="post-text__more-link" href="#">Читать далее</a>';
};

function get_link_content_type($id) {
    $scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
    $url = "/" . $scriptname . "?ID=" . $id;

    return $url;
};

function get_url_post($id) {
    return "/post.php?ID=" . $id;
};

$con = get_db_connection();
$user_name = 'Olya';
$title = 'readme: популярное';
$content_types = get_post_content_types($con);
$active_type_content_id = filter_input(INPUT_GET, 'ID');
$popular_posts = get_popular_posts($con, $active_type_content_id);

$page_content = include_template('main.php', [
    'popular_posts' => $popular_posts,
    'content_types' => $content_types,
    'active_type_content_id' => $active_type_content_id
]);
$page = include_template('layout.php', [
    'page_content' => $page_content,
    'user_name' => $user_name,
    'title' => $title
]);
print($page);
