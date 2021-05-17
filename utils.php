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

function upload_file($file_url) {
    $image_content = file_get_contents($file_url);
    $file_name = basename($file_url);
    $file_path = __DIR__ . '/uploads/';
    file_put_contents($file_path . $file_name, $image_content);

    return '/uploads/' .  $file_name;
};

function save_image($file) {
    $file_name = $file['name'];
    $file_path = __DIR__ . '/uploads/';
    move_uploaded_file($file['tmp_name'], $file_path . $file_name);

    return '/uploads/' . $file_name;
};


function save_post($con, $post, $post_type_id, $file_url = null) {
    $data = [
        'id' => null,
        'date_add' => date('Y-m-d H:i:s'),
        'title' => $post['post-heading'],
        'content' => '',
        'author' => null,
        'shown_count' => 0,
        'user_id' => 1,
        'content_type_id' => $post_type_id
    ];

    switch ($post['active-tab']) {
        case 'photo':
            if ($file_url) {
                $data['content'] = $file_url;
            } else {
                $data['content'] = $post['photo-url'];
            }
            break;

        case 'video':
            $data['content'] = $post['video-url'];
            break;

        case 'text':
            $data['content'] = $post['post-text'];
            break;

        case 'quote':
            $data['content'] = $post['cite-text'];
            $data['author'] = $post['quote-author'];
            break;

        case 'link':
            $data['content'] = $post['post-link'];
            break;
    }

    $fields = [];
    $data_for_query = [];
    foreach ($data as $key => $item) {
        $fields[] = "{$key} = ?";
        array_push($data_for_query, $item);
    }

    $fields_for_query = implode(', ', $fields);
    $sql = "INSERT INTO post SET {$fields_for_query}";
    $stmt = db_get_prepare_stmt(
        $con,
        $sql,
        $data_for_query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_get_result($stmt);
    return mysqli_insert_id($con);
};

function save_tags($con, $hashtags, $post_id) {
    $new_unique_hashtags = array_unique((explode(' ', htmlspecialchars($hashtags))));
    $sql_hashtags_db = "SELECT * FROM hashtag";
    $result_hashtags_db = mysqli_query($con, $sql_hashtags_db);
    $hashtags_by_db = [];

    if ($result_hashtags_db) {
        $hashtags_by_db = mysqli_fetch_all($result_hashtags_db, MYSQLI_ASSOC);

        foreach ($new_unique_hashtags as $hashtag) {
            $hashtag_value = substr($hashtag, 1, strlen($hashtag));
            $hashtag_id = null;
            $repeat_hashtag_key = array_search($hashtag_value, array_column($hashtags_by_db, 'title'));

            if ($repeat_hashtag_key) {
                $hashtag_id = $hashtags_by_db[$repeat_hashtag_key]['id'];
            } else {
                $sql_hashtag_title = "INSERT INTO hashtag SET title = ?";
                $stmt = db_get_prepare_stmt(
                    $con,
                    $sql_hashtag_title,
                    [$hashtag_value]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_get_result($stmt);
                $hashtag_id = mysqli_insert_id($con);
            }

            $sql_add_post_hashtag = "INSERT INTO PostHashtag SET post_id = ?, hashtag_id = ?";
            $stmt_post_hashtags = db_get_prepare_stmt(
                $con,
                $sql_add_post_hashtag,
                [$post_id, $hashtag_id]);
            mysqli_stmt_execute($stmt_post_hashtags);
            mysqli_stmt_get_result($stmt_post_hashtags);
        }
    }
};
