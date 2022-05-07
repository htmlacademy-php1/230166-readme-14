<?php
require_once 'config/init.php';

$page_title = 'readme: регистрация';
$errors = [];
$user = [];
$required = [
    'email' => 'Электронная почта',
    'login' => 'Логин',
    'tag' => 'Тэги',
    'password' => 'Пароль',
    'password-repeat' => 'Повтор пароля',
];

$page_content = include_template('registration.php', [
    'errors' => $errors,
    'user' => $user
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'login' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], true);
    $file_name = $_FILES['avatar']['name'] ?? NULL;

    $errors = get_required_errors($user, $required);

    // Валидация формата почты
    if (!isset($errors['email'])) {
        $errors['email'] = validate_email($user['email']);
    }

    // Валидация на существование почты
    if (!isset($errors['email'])) {
        if (check_user_email($con, $user['email'])) {
            $errors['email'] = "Пользователь с этим email уже зарегистрирован";
        }
    }

    // Валидация на существование логина
    if (!isset($errors['login'])) {
        if (check_user_login($con, $user['login'])) {
            $errors['login'] = "Пользователь с этим логином уже зарегистрирован";
        }
    }

    // Проверка на правильность повтора пароля
    if (!isset($errors['password'])) {
        $repeat_password = filter_post_parametr('password-repeat');

        if ($repeat_password !== $user['password']) {
            $errors['password-repeat'] = "Повтор пароля. Неправильно заполненное поле.";
        }
    }

    if ($file_name) {
        $tmp_name = $_FILES['avatar']['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        // Валидация поля «Выбор файла» на допустимые форматы
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

    var_dump($user);

    $errors = array_filter($errors);

    if(count($errors)) {
        $page_content = include_template('registration.php', [
            'errors' => $errors,
            'user' => $user
        ]);
    } else {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, login, password, avatar) VALUES (?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, [$user['email'], $user['login'], $password, $avatar]);
        $result = mysqli_stmt_execute($stmt);

        header('Location: registration.php');

        if (!$result) {
            show_error(mysqli_error($con));
        }
    }
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_title' => $page_title,
    'page_content' => $page_content,
]);

print($layout_content);
