<?php
require('helpers.php');
$con = mysqli_connect("127.0.0.1:3306", "root", "root","readme");
mysqli_set_charset($con, "utf8");

$content_types = array();
$popular_posts = array();

if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $sql_content_type = "SELECT * FROM content_type";
    $result_content_type = mysqli_query($con, $sql_content_type);

    if ($result_content_type) {
        $content_types = mysqli_fetch_all($result_content_type, MYSQLI_ASSOC);
    }

    $sql_post_popular =
        "SELECT p.id, p.title, p.content, p.author, u.user_name, u.avatar, p.shown_count, u.login, c.class_name as type, p.date_add
        FROM post p JOIN user u ON p.user_id = u.id
        JOIN content_type c ON p.content_type_id = c.id
        ORDER BY p.shown_count DESC LIMIT 6;";

    $result_popular_post = mysqli_query($con, $sql_post_popular);

    if ($result_popular_post) {
        $popular_posts = mysqli_fetch_all($result_popular_post, MYSQLI_ASSOC);
    }
}


$user_name = 'Olya';

$title = 'readme: популярное';

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

function format_date($date) {
    date_default_timezone_set('Europe/Moscow');
    $cur_date = date_create("now");
    $diff = date_diff($cur_date, date_create($date));
    $minutes = date_interval_format($diff, "%i");
    $hours = date_interval_format($diff, "%h");
    $days = date_interval_format($diff, "%d");
    $months = date_interval_format($diff, "%m");

    if ($months > 0) {
        return $months . ' ' .
            get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' назад';
    } elseif ($days > 6) {
        return floor($days / 7) . ' ' .
            get_noun_plural_form(floor(($days / 7)), ' неделю', ' недели', ' недель') . ' назад';
    } elseif ($days > 0) {
        return $days . ' ' .
            get_noun_plural_form($days, 'день', 'дня', 'дней') . ' назад';
    } elseif ($hours > 0) {
        return $hours . ' ' .
            get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
    } elseif ($minutes > 0) {
        return $minutes . ' ' .
            get_noun_plural_form($minutes, 'минуту', 'минуты', 'минут') . ' назад';
    } elseif ($minutes <= 0) {
        return 'только что';
    } else {
        return '';
    }
};

$page_content = include_template('main.php', ['popular_posts' => $popular_posts, 'content_types' => $content_types]);
$page = include_template('layout.php', [
    'page_content' => $page_content,
    'user_name' => $user_name,
    'title' => $title
]);
print($page);
