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
    $file_name = $_FILES['avatar']['name'] ?? NULL;

    // Валидация обязательных полей
    $errors = get_required_errors($form, $required);

    // Валидация формата почты
    if (!isset($errors['email'])) {
        $errors['email'] = validate_email($form['email']);
    }

    // Валидация на существование почты
    if (!isset($errors['email'])) {
        if (check_user_email($con, $form['email'])) {
            $errors['email'] = "Пользователь с этим email уже зарегистрирован";
        }
    }

    // Валидация на существование логина
    if (!isset($errors['login'])) {
        if (check_user_login($con, $form['login'])) {
            $errors['login'] = "Пользователь с этим логином уже зарегистрирован";
        }
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

    $errors = array_filter($errors);

    if(empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, login, password, avatar) VALUES (?, ?, ?, ?)";
        $user = [$form['login'], $password, $avatar];
        $stmt = db_get_prepare_stmt($con, $sql, $user);
        $result = mysqli_stmt_execute($stmt);

        header('Location: reg.php');
        exit();

        if (!$result) {
            show_error(mysqli_error($con));
        }
    }
}

$page_content = include_template('reg.php', [
    'errors' => $errors,
    'user' => $form
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content
]);

print($page_layout);
