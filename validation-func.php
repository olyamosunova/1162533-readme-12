<?php

/**
 * Return result array with message and flag
 *
 * @param mixed $value
 * @param bool $result
 * @param string $message
 * @return array
 */
function validation_result($value = null, bool $result = true, string $message = 'ok'): array {
    return ['is_valid' => $result, 'message' => $message, 'value' => $value];
};

/**
 * Run validation functions
 *
 * @param array $form_validations
 * @param array $error_field_titles
 * @return array
 */
function validation_validate(array $form_validations, array $error_field_titles): array {
    $errors = [];
    $values = [];

    foreach ($form_validations as $key => $validations) {
        foreach ($validations as $validation) {
            $result = $validation($key);

            if (!$result['is_valid']) {
                $errors += [
                    $key => [
                        'title' => $error_field_titles[$key],
                        'message' => $result['message'],
                    ]
                ];
            }
            $values[$key] = $result['value'];
        }
    }

    return ['errors' => $errors, 'values' => $values];
};

function validate_filled($name) {
    if (empty($_POST[$name])) {
        return validation_result(null, false, 'Это поле должно быть заполнено');
    }

    return validation_result($_POST[$name]);
};

function validate_hashtags($name) {
    if (empty($_POST[$name])) {
        return validation_result($_POST[$name]);
    } else {
        $error = null;
        $hashtags = explode(' ', $_POST[$name]);

        foreach ($hashtags as $hashtag) {
            if (substr($hashtag, 0, 1) !== '#') {
                $error = 'Хэштег должен начинаться со знака решетки';
            } elseif (strrpos($hashtag, '#') > 0) {
                $error = 'Хэш-теги разделяются пробелами';
            } elseif (strlen($hashtag) < 2) {
                $error = 'Хэш-тег не может состоять только из знака решетки';
            }
        }

        if (!is_null($error)) {
            return validation_result(null, false, $error);
        }
    }

    return validation_result($_POST[$name]);
};

function validate_upload_photo($name, $file_field) {
    $valid_image_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

    if (empty($_POST[$name]) && (empty($_FILES[$file_field]) || $_FILES[$file_field]['error'] === 4)) {
        return validation_result(null, false, 'Вы должны загрузить фото, либо прикрепить ссылку из интернета');
    } elseif(!empty($_POST[$name])) {
        $tmp = explode('.', $_POST[$name]);
        $type = 'image/' . end($tmp);

        if(!in_array($type, $valid_image_types)) {
            return validation_result(null, false, 'Неверный формат загружаемого файла.');
        }

        if (!file_get_contents($_POST[$name])) {
            return validation_result(null, false, 'Не удалось найти изображение. Проверьте ссылку.');
        }
    }

    return validation_result([$name => ($_POST[$name] ?? null), $file_field => ($_FILES[$file_field] ?? null)]);
};

function validate_url($name) {
    if ($_POST[$name] && !filter_var($_POST[$name], FILTER_VALIDATE_URL)) {
        return validation_result(null, false, 'Значение поля должно быть корректным URL-адресом');
    }

    return validation_result($_POST[$name]);
};

function validate_photo($name) {
    if ($_FILES[$name] && $_FILES[$name]['error'] !== 4) {
        $file_type = $_FILES[$name]['type'];

        $valid_image_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

        if (in_array($file_type, $valid_image_types)) {
            return validation_result($_FILES[$name]);
        }

        return validation_result(null, false, 'Неверный формат загружаемого файла. Допустимый формат: ' . implode(' , ', $valid_image_types));
    }

    return validation_result($_FILES[$name]);
};

function validate_email($name) {
    if ($_POST[$name] && !filter_var($_POST[$name], FILTER_VALIDATE_EMAIL)) {
        return validation_result(null, false, 'Значение поля должно быть корректным email-адресом');
    }

    return validation_result($_POST[$name]);
};

function validate_passwords_repeat($pass1, $pass2) {
    if ($_POST[$pass1] !== $_POST[$pass2]) {
        return validation_result(null, false, 'Пароли не совпадают');
    }

    return validation_result($_POST[$pass1]);
};

function validate_password($name) {
    if (strlen($_POST[$name]) < 5) {
        return validation_result(null, false, 'Пароль должен состоять не менее чем из 5 символов');
    }

    if (!preg_match('/^\S*$/', $_POST[$name])) {
        return validation_result(null, false, 'Пароль не должен содержать пробелы');
    }

    return validation_result($_POST[$name]);
};


