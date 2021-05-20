<?php
require('helpers.php');
require('utils.php');
require('validation-func.php');
require('db.php');

$con = get_db_connection();
$title = 'readme: регистрация';

$form_validations = [
    'registration-email' => [
        0 => function ($name) {
            return validate_filled($name);
        },
        1 => function($name) {
            return validate_email($name);
        }
    ],
    'registration-login' => [
        0 => function ($name) {
            return validate_filled($name);
        }
    ],
    'registration-password' => [
        0 => function ($name) {
            return validate_filled($name);
        },
        1 => function ($name) {
            return validate_password($name);
        }
    ],
    'registration-password-repeat' => [
        0 => function ($name) {
            return validate_filled($name);
        },
        1 => function($name) {
            return validate_passwords_repeat('registration-password', $name);
        }
    ],
    'userpic-file' => [
        0 => function($name) {
            return validate_photo($name);
        }
    ]
];

$error_field_titles = [
    'registration-email' => 'Электронная почта',
    'registration-login' => 'Логин',
    'registration-password' => 'Пароль',
    'registration-password-repeat' => 'Повтор пароля',
    'userpic-file' => 'Аватар пользователя'
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($form_validations as $key => $validations) {
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

        if (isset($_FILES['userpic-file']) && $_FILES['userpic-file']['error'] === 0) {
            $photo_path = '/uploads/users/';
            $file_url = save_image($_FILES['userpic-file'], $photo_path);
        }

        $result_check_email = check_email_in_db($con, $_POST['registration-email']);

        if (!$result_check_email) {
            register_user($con, $_POST, $file_url);
            header("Location: /");
        } else {
            $errors += ['registration-email' => [
                'title' => $error_field_titles['registration-email'],
                'message' => $result_check_email
            ]];
        }
    }
}

$page_content = include_template('registration.php', [
    'errors' => $errors
]);

$page = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => $title,
    'is_registration_page' => true
]);

print($page);
