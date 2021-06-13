<?php
require('init.php');
require('helpers.php');
require('utils.php');
require('validation-func.php');
require('db.php');

init_check_auth('/');

$con = get_db_connection();
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

$post_form_title = [
    'photo' => 'Форма добавления фото',
    'video' => 'Форма добавления видео',
    'text' => 'Форма добавления текста',
    'quote' => 'Форма добавления цитаты',
    'link' => 'Форма добавления ссылки',
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
                return validate_upload_photo($name, 'userpic-file-photo');
            },
            2 => function($name) {
                if (isset($_FILES['userpic-file-photo']) && $_FILES['userpic-file-photo']['error'] === 0) {
                    return validation_result([$name => ($_POST[$name] ?? null), 'userpic-file-photo' => $_FILES['userpic-file-photo']]);
                }
                return validation_result([$name => ($_POST[$name] ?? null), 'userpic-file-photo' => null]);
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

    $validation_result = validation_validate($form_validations[$active_tab], $error_field_titles);
    $errors = $validation_result['errors'];
    $values = $validation_result['values'];
    $values['active-tab'] = $active_tab;

    if (empty($errors)) {
        $file_url = null;

        if ($active_tab === 'photo') {

            $photo_path = '/uploads/';

            if (isset($values['photo-url']['userpic-file-photo'])) {
                $file_url = save_image($values['photo-url']['userpic-file-photo'], $photo_path);
            } else {
                $file_url = upload_file($values['photo-url']['photo-url'], $photo_path);
            }
        }

        $post_type_id = $content_types[array_search($active_tab, array_column($content_types, 'title'))]['id'];
        $post_id = save_post($con, $values, $post_type_id, $file_url, $_SESSION['id']);
        if (!empty($values['post-tags'])) {
            save_tags($con, $values['post-tags'], $post_id);
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

$form_fields = include_template('adding-posts/adding-post-' . $active_tab  . '.php', [
    'active_tab' => $active_tab,
    'errors' => $errors
]);

$active_form = include_template('adding-posts/adding-post-form.php', [
    'active_tab' => $active_tab,
    'errors' => $errors,
    'form_title' => $post_form_title[$active_tab],
    'fields' => $form_fields
]);

$page_content = include_template('adding-post.php', [
    'post_tabs' => $post_tabs,
    'active_tab' => $active_tab,
    'errors' => $errors,
    'active_form' => $active_form
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => $title,
    'is_auth' =>$_SESSION['is_auth'],
    'user_name' => $_SESSION['user_name'],
    'user_avatar' => $_SESSION['avatar']
]);

print($page);
