<?php

require_once 'config/init.php';

$post_id = intval(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT));
$current_page = $_SERVER['HTTP_REFERER'];
$current_user_id = (int)$current_user['id'];

$comment = '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = filter_input(INPUT_POST, 'comment');

    if (!$comment) {
        $errors['comment'] = 'Это поле должно быть заполнено';
    } else {
        add_comment($con, $post_id, $current_user_id, $comment);

        header('Location: ' . $current_page);
    }
}
