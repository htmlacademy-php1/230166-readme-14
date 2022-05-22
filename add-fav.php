<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$post_id = intval(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT));
$current_page = $_SERVER['HTTP_REFERER'];
$current_user_id = (int)$current_user['id'];

if (!$post_id || !check_post_id($con, $post_id)) {
    show_error('Пост будет написан в ближайшее время.');
}

if (check_is_fav($con, $post_id, $current_user_id)) {
    remove_fav($con, $post_id, $current_user_id);
} else {
    add_fav($con, $post_id, $current_user_id);
}

header('Location: ' . $current_page);
