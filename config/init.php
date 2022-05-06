<?php

require_once 'config/config.php';
require_once 'config/db.php';
require_once 'helpers.php';
require_once 'models.php';
require_once 'functions.php';
require_once 'data.php';

$con = mysqli_connect($user['host'], $user['user'], $user['password'], $user['database']);
mysqli_set_charset($con, 'utf8mb4');

if (!$con) {
    $error = mysqli_connect_error();
    show_error($error);
}
