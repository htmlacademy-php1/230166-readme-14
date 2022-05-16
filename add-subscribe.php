<?php
require_once 'config/init.php';

$user_id = intval(filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT));
$current_page = $_SERVER['HTTP_REFERER'];
$current_user_id = (int)$current_user['id'];

if (!$user_id || !check_user_id($con, $user_id)) {
    show_error('Такой пользователь не зарегистрирован.');
}

if (!check_subscribe($con, $user_id, $current_user_id)) {
    add_subscribe($con, $user_id, $current_user_id);
    var_dump('sdfdf');
} else {
    remove_subcribe($con, $user_id, $current_user_id);
}

header('Location: ' . $current_page);
