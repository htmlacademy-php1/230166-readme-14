<?php

require_once 'config/init.php';

$page_title = 'readme: публикация';

$post_id = intval(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT));

if (!$post_id && !check_post_id($con, $post_id)) {
    show_error('Пост будет написан в ближайшее время!');
}

$current_user_id = (int)$current_user['id'];

$post = get_post_by_id($con, $post_id, $current_user_id);

$user_id = (int) $post['user_id'];
$user = get_user_by_id($con, $user_id, $current_user_id);

$all_comments = get_comments($con, $post_id);
$is_show_comments = filter_input(INPUT_GET, 'is_show_comments');

add_views($con, $post_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = trim(filter_input(INPUT_POST, 'comment'));

    if (!check_user_id($con, $user_id)) {
        $error = "Такой пользователь не зарегистрирован!";
    } elseif ($user_id === $current_user_id) {
        $error = "Нельзя отправить сообщение самому себе!";
    } elseif (!$comment) {
        $error = "Это поле должно быть заполнено!";
    }

    if (!$error) {
        add_comment($con, $post_id, $current_user_id, $comment);

        header('Location: post.php?post_id=' . $post_id);
    }
}

$page_content = include_template('post-details.php', [
    'user' => $user,
    'current_user' => $current_user,
    'post' => $post,
    'is_show_comments' => $is_show_comments,
    'comment' => $comment ?? NULL,
    'error' => $error ?? NULL
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
