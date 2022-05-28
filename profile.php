<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: профиль';

$tab = filter_input(INPUT_GET, 'tab') ?? 'posts';

$current_user_id = (int)$current_user['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = trim(filter_input(INPUT_POST, 'comment'));
    $post_id = filter_input(INPUT_POST, 'post_id');
    $user_id = filter_input(INPUT_POST, 'user_id');

    if (!check_user_id($con, $user_id)) {
        $comment_error_text = "Такой пользователь не зарегистрирован.";
    } elseif ($user_id === $current_user_id) {
        $comment_error_text = "Нельзя отправить сообщение самому себе.";
    } elseif (!$comment) {
        $comment_error_text = "Это поле должно быть заполнено.";
    }

    if (!$comment_error_text) {
        add_comment($con, $post_id, $current_user_id, $comment);
        header('Location: profile.php?user_id=' . $user_id);
    } else {
        $comment_error_id = $post_id;
    }
} else {
    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
}

if (!$user_id && !check_user_id($con, $user_id)) {
    show_error('Такой пользователь не зарегистрирован.');
}

$user = get_all_user_data($con, $user_id, $current_user_id);
$posts = get_user_posts($con, $user_id, $current_user_id);
$favs = get_favs($con, $user_id);
$subscribers = get_subscrubers($con, $user_id, $current_user_id);

$page_content = include_template('profile.php', [
    'user' => $user,
    'current_user' => $current_user,
    'posts' => $posts,
    'user_id' => $user_id,
    'tab' => $tab,
    'favs' => $favs,
    'subscribers' => $subscribers,
    'comment_error_id' => $comment_error_id ?? null,
    'comment_error_text' => $comment_error_text ?? null,
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
