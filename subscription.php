<?php
require('init.php');
require('helpers.php');
require('db.php');

$con = get_db_connection();
$path = $_SERVER['HTTP_REFERER'];

if (!empty($_GET) &&
    !empty($_GET['user_id']) &&
    !empty($_GET['follower_id']) &&
    !empty($_GET['action'])) {
    if (change_subscription($con, $_GET) == 0) {
        header("Location: $path");
    } else {
        print_r('Не получилось произвести действия с подпиской');
    }
}


