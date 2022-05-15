<?php

require_once 'config/init.php';

$page_title = 'readme: регистрация';
$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = filter_input_array(INPUT_POST, ['login' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], true);
    $form = trim_array($form);
    $required = [
        'login' => 'Логин',
        'password' => 'Пароль',
    ];

    $errors = get_required_errors($form, $required);

    if (!isset($errors['login']) && !filter_var($form['login'], FILTER_VALIDATE_EMAIL)) {
        $errors['login'] = 'Неверный формат email';
    }

    $errors = array_filter($errors);
    $current_user = get_user_by_email($con, $form['login']);

    if (empty($errors) and !$current_user) {
        $errors['login'] = 'Такой пользователь не найден';
    }

    if (empty($errors) and $current_user) {
        if (password_verify($form['password'], $current_user['password'])) {
            $_SESSION['current_user'] = $current_user;
        } else {
            $errors['password'] = "Пароли не совпадают";
        }
    }

    if (isset($_SESSION['user'])) {
        header('Location: feed.php');
        exit();
    }
}

$main_layout = include_template('main-page.php', [
    'page_title' => $page_title,
    'form' => $form,
    'errors' => $errors
]);

print($main_layout);
