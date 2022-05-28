<?php

require_once 'config/init.php';

$page_title = 'readme: регистрация';
$errors = [];
$form = [];
$required = [
    'email' => 'Электронная почта',
    'login' => 'Логин',
    'tag' => 'Тэги',
    'password' => 'Пароль',
    'password-repeat' => 'Повтор пароля',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'login' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'password-repeat' => FILTER_DEFAULT], true);
    $form = trim_array($form);
    $file_name = $_FILES['avatar']['name'] ?? null;

    // Валидация обязательных полей
    $errors = get_required_errors($form, $required);

    // Валидация формата почты
    if (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неправильный формат почты';
    }

    // Валидация почты на количество символов
    if (!isset($errors['email']) && !check_length_of_string($form['email'], 5, 128)) {
        $errors['email'] = "Электронная почта. Значение должно быть от 5 до 128 символов";
    }

    // Валидация на существование почты
    if (!isset($errors['email']) && check_user_email($con, $form['email'])) {
        $errors['email'] = "Электронная почта. Пользователь с этим email уже зарегистрирован";
    }

    // Валидация логина на количество символов
    if (!isset($errors['login']) && !check_length_of_string($form['login'], 2, 255)) {
        $errors['login'] = "Логин. Значение должно быть от 2 до 255 символов";
    }

    // Валидация на существование логина
    if (!isset($errors['login']) && check_user_login($con, $form['login'])) {
        $errors['login'] = "Логин. Пользователь с этим логином уже зарегистрирован";
    }

    // Валидация пароля на количество символов
    if (!isset($errors['password']) && !check_length_of_string($form['password'], 6, 6)) {
        $errors['password'] = "Пароль. Пароль должен состоять из 6 символов";
    }

    // Проверка на правильность повтора пароля
    if (!isset($errors['password'])) {
        if ($form['password-repeat'] !== $form['password']) {
            $errors['password-repeat'] = "Повтор пароля. Неправильно заполненное поле.";
        }
    }

    if ($file_name) {
        $tmp_name = $_FILES['avatar']['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type === 'image/jpeg') {
            $ext = '.jpg';
        } elseif ($file_type === 'image/png') {
            $ext = '.png';
        } elseif ($file_type === 'image/gif') {
            $ext = '.gif';
        }

        if ($ext) {
            $filename = uniqid() . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/'. $filename);
            $avatar = "uploads/". $filename;
        } else {
            $errors['avatar'] = 'Допустимые форматы файлов: jpg, jpeg, png, gif.';
        }
    } else {
        $errors['avatar'] = 'Вы не загрузили аватарку';
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, login, password, avatar) VALUES (?, ?, ?, ?)";
        $user = [$form['email'], $form['login'], $password, $avatar];
        $stmt = db_get_prepare_stmt($con, $sql, $user);
        $result = mysqli_stmt_execute($stmt);

        header('Location: index.php');
        exit();

        if (!$result) {
            show_error(mysqli_error($con));
        }
    }
}

$page_content = include_template('reg.php', [
    'errors' => $errors,
    'form' => $form
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'page_content' => $page_content,
    'current_user' => $current_user
]);

print($page_layout);
