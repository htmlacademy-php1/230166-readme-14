<?php

require_once 'config/init.php';

$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = filter_input_array(INPUT_POST, ['login' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], true);
    $form = trim_array($form);
    $required = [
        'login' => 'Логин',
        'password' => 'Пароль',
    ];

    $email = $form['login'];
    $password = $form['password'];

    $errors = get_required_errors($form, $required);

    if (!isset($errors['login']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['login'] = 'Неверный формат email';
    }

    if (!isset($errors['login']) and !check_user_email($con, $email)) {
        $errors['login'] = 'Такой пользователь не найден';
    }

    $errors = array_filter($errors);

    $current_user = get_сurrent_user($con, $email);

    if (empty($errors) && $current_user) {
        if (password_verify($password, $current_user['password'])) {
            $_SESSION['current_user'] = $current_user;
        } else {
            $errors['password'] = "Пароли не совпадают";
        }
    }

    if (isset($_SESSION['current_user'])) {
        header('Location: feed.php');
        exit();
    }
}

$main_layout = include_template('main-page.php', [
        'page_title' => 'readme',
        'form' => $form,
        'errors' => $errors
    ]
);
print($main_layout);
