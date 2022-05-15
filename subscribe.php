<?php
require_once 'config/init.php';

$post_id = intval(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT));
$current_page = $_SERVER['HTTP_REFERER'];

if (!$post_id || !check_post_id($con, $post_id)) {
    show_error('Пост будет написан в ближайшее время.');
}

$is_fav = check_is_fav($con, $post_id, $current_user['id']);

if ($is_fav) {
    $sql = "DELETE FROM fav WHERE user_id = {$current_user['id']} AND post_id = {$post_id}";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error(mysqli_error($con));
    }
} else {
    $sql = "INSERT INTO fav (user_id, post_id) VALUES ({$current_user['id']}, $post_id)";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error(mysqli_error($con));
    }
}

header('Location: ' . $current_page);
