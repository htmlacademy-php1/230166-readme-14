<?php
require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';
require_once 'sql_functions.php';

$link = mysqli_connect($user['host'], $user['user'], $user['password'], $user['database']);
mysqli_set_charset($link, 'utf8mb4');

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
    die();
}
