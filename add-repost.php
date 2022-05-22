<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$current_user_id = (int)$current_user['id'];

if (!$post_id || !check_post_id($con, $post_id)) {
    show_error('Пост будет написан в ближайшее время!');
}

add_repost($con, $post_id, $current_user_id);

header('Location: profile.php?user_id=' . $current_user_id);
