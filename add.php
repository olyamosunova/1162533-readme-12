<?php
require('helpers.php');
require('utils.php');
require('validation-func.php');
require('db.php');

$con = get_db_connection();
$user_name = 'Olya';
$title = 'readme: добавление публикации';
$content_types = get_post_content_types($con);
$active_tab = isset($_POST['active-tab']) ? $_POST['active-tab'] : filter_input(INPUT_GET, 'tab') ?? 'photo';

$post_tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];

$form_validations = [
    'photo' => [
        'post-heading' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'photo-url' => [
            0 => function($name) {
                return validate_url($name);
            },
            1 => function($name) {
                return validate_upload_photo($name);
            }
        ],
        'post-tags' => [
            0 => function ($name) {
                return validate_hashtags($name);
            }
        ],
        'userpic-file-photo' => [
            0 => function($name) {
                return validate_photo($name);
            }
        ]
    ],
    'video' => [
        'post-heading' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'video-url' => [
            0 => function ($name) {
                return validate_filled($name);
            },
            1 => function($name) {
                return validate_url($name);
            },
            2 => function($name) {
                if ($_POST[$name] && is_string(check_youtube_url($_POST[$name]))) {
                    return check_youtube_url($_POST[$name]);
                } else {
                    return false;
                }
            }
        ],
        'post-tags' => [
            0 => function ($field) {
                return validate_hashtags($field);
            }
        ]
    ],
    'text' => [
        'post-heading' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'post-text' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'post-tags' => [
            0 => function ($name) {
                return validate_hashtags($name);
            }
        ]
    ],
    'quote' => [
        'post-heading' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'cite-text' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'quote-author' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'post-tags' => [
            0 => function ($name) {
                return validate_hashtags($name);
            }
        ]
    ],
    'link' => [
        'post-heading' => [
            0 => function ($name) {
                return validate_filled($name);
            }
        ],
        'post-link' => [
            0 => function ($name) {
                return validate_filled($name);
            },
            1 => function($name) {
                return validate_url($name);
            }
        ],
        'post-tags' => [
            0 => function ($name) {
                return validate_hashtags($name);
            }
        ],
    ]
];

$error_field_titles = [
    'post-heading' => 'Заголовок',
    'post-tags' => 'Теги',
    'photo-url' => 'Ссылка из интернета',
    'userpic-file-photo' => 'Файл',
    'video-url' => 'Ссылка Youtube',
    'post-text' => 'Текст поста',
    'cite-text' => 'Текст цитаты',
    'quote-author' => 'Автор цитаты',
    'post-link' => 'Ссылка'
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($form_validations[$active_tab] as $key => $validations) {
        foreach ($validations as $validation) {
            if ($validation($key)) {
                $errors += [$key => [
                    'title' => $error_field_titles[$key],
                    'message' => $validation($key)
                ]];
            }
        }
    }

    if (empty($errors)) {
        $file_url = null;

        if ($active_tab === 'photo') {
            $file_url = upload_file('userpic-file-photo', 'photo-url');
        }

        $post_type_id = $content_types[array_search($active_tab, array_column($content_types, 'title'))]['id'];
        $post_id = save_post($con, $_POST, $post_type_id, $file_url);

        if (isset($_POST['post-tags'])) {
            save_tags($con, $_POST['post-tags'], $post_id);
        }

        $URL = '/post.php?ID=' . $post_id;
        header("Location: $URL");
    }
}

function get_tabs_link($name) {
    $scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
    $url = "/" . $scriptname . "?tab=" . $name;

    return $url;
};

function get_post_val($name) {
    return count($_POST) && $_POST[$name] ? htmlspecialchars($_POST[$name]) : '';
};

$active_form = include_template('adding-posts/adding-post-' . $active_tab  . '.php', [
    'active_tab' => $active_tab,
    'errors' => $errors
]);

$page_content = include_template('adding-post.php', [
    'post_tabs' => $post_tabs,
    'active_tab' => $active_tab,
    'errors' => $errors,
    'active_form' => $active_form
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'user_name' => $user_name,
    'title' => $title
]);

print($page);
