<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$user_id = intval(filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT));
$current_page = $_SERVER['HTTP_REFERER'];
$current_user_id = (int)$current_user['id'];

if (!$user_id) {
    show_error('Такой пользователь не зарегистрирован.');
}

if (!check_subscribe($con, $user_id, $current_user_id)) {
    add_subscribe($con, $user_id, $current_user_id);

    $user = get_user($con, $user_id);

    send_email_subscribe($user, $current_user);
} else {
    remove_subcribe($con, $user_id, $current_user_id);
}

header('Location: ' . $current_page);
