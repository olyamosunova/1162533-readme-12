<?php
/**
 * Возвращает дату в нужном формате
 * @param date $date
 * @return string
 */
function format_publication_date($date) {
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
            get_noun_plural_form($years, 'год', 'года', 'лет') . ' назад';
    }
    if ($months > 0 && $months < 12) {
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

/**
 * Возвращает страницу "не найдено"
 * @param string $user_name
 */
function not_found_page($user_name)
{
    $page_content = include_template('not-found-page.php');
    $page = include_template('layout.php', [
        'page_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'readme: страница не найдена',
    ]);

    print($page);
    http_response_code(404);
    exit();
}

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
};

function get_text_count_publications($count) {
    return get_noun_plural_form($count, 'публикация', 'публикации', 'публикаций');
};

function get_text_count_shown($count) {
    return $count . " " . get_noun_plural_form($count, 'просмотр', 'просмотра', 'просмотров');
};
