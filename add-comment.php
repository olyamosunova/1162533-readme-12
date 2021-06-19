<?php
require('init.php');
require('helpers.php');
require('db.php');

$con = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST &&
        !empty($_POST['message']) &&
        !empty($_POST['post']) &&
        !empty($_POST['user'])
    ) {
        send_comment($con, $_POST);
    }

    $path = $_SERVER['HTTP_REFERER'];
    header("Location: $path");
}
