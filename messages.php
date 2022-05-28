<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: личные сообщения';
$current_user_id = (int)$current_user['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = trim(filter_input(INPUT_POST, 'comment'));
    $user_id = filter_input(INPUT_POST, 'user_id');

    if (!check_user_id($con, $user_id)) {
        $error = "Такой пользователь не зарегистрирован!";
    } elseif ($user_id === $current_user_id) {
        $error = "Нельзя отправить сообщение самому себе!";
    } elseif (!$comment) {
        $error = "Это поле должно быть заполнено!";
    }

    if (!$error) {
        add_message($con, $current_user_id, $user_id, $comment);
        header('Location: messages.php?user_id=' . $user_id);
    }
} else {
    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
}

if ($user_id && !check_user_id($con, $user_id)) {
    show_error('Такой пользователь не зарегистрирован!');
}

$users = get_all_communicate_users($con, $current_user_id);

if (!$user_id && $users) {
    $user_id = $users[0]['id'];

    $all_user_ids = array_column($users, 'id');

    if (!in_array($user_id, $all_user_ids)) {
        $start_user = get_communicate_user($con, $user_id);
    }
} elseif ($user_id && !$users) {
    $start_user = get_communicate_user($con, $user_id);
}

if ($user_id) {
    $messages = get_messages($con, $user_id, $current_user_id);
}

$page_content = include_template('messages.php', [
    'current_user' => $current_user,
    'user_id' => $user_id ?? null,
    'messages' => $messages ?? [],
    'users' => $users ?? [],
    'error' => $error ?? null,
    'start_user' => $start_user ?? null,
    'comment' => $comment ?? null
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
