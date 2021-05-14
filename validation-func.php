<?php

function validate_filled($name) {
    if (empty($_POST[$name])) {
        return 'Это поле должно быть заполнено';
    }

    return false;
};

function validate_hashtags($name) {
    if (empty($_POST[$name])) {
        return false;
    } else {
        $error = false;
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

        return $error;
    }
};

function validate_upload_photo($name) {
    $valid_image_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

    if (empty($_POST[$name]) && (empty($_FILES['userpic-file-photo']) || $_FILES['userpic-file-photo']['error'] === 4)) {
        return 'Вы должны загрузить фото, либо прикрепить ссылку из интернета';
    } elseif(!empty($_POST[$name])) {
        $tmp = explode('.', $_POST[$name]);
        $type = 'image/' . end($tmp);

        if(!in_array($type, $valid_image_types)) {
            return 'Неверный формат загружаемого файла.';
        }

        if (!file_get_contents($_POST[$name])) {
            return 'Не удалось найти изображение. Проверьте ссылку.';
        }
    }

    return false;
};

function validate_url($name) {
    if ($_POST[$name] && !filter_var($_POST[$name], FILTER_VALIDATE_URL)) {
        return "Значение поля должно быть корректным URL-адресом";
    }

    return false;
};

function validate_photo($name) {
    if ($_FILES[$name] && $_FILES[$name]['error'] !== 4) {
        $file_type = $_FILES[$name]['type'];

        $valid_image_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

        if (in_array($file_type, $valid_image_types)) {
            return false;
        }

        return 'Неверный формат загружаемого файла. Допустимый формат: ' . implode(' , ', $valid_image_types);
    }
};
