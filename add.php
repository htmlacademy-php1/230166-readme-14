<?php
require_once 'config/init.php';

$page_title = 'readme: добавление публикации';
$types = get_types($link);
$types_id = array_column($types, 'id');
$errors = [];
$current_type_id = (int)get_parametr('type_id');
$post = [];
$required = ['title' => 'Заголовок', 'text' => 'Текст поста', 'tag' => 'Тэги', 'link' => 'Ссылка', 'quote' => 'Текст цитаты', 'caption' => 'Автор', 'youtube_url' => 'Ссылка youtube'];


$page_content = include_template('adding-post.php', [
    'types' => $types,
    'errors' => $errors,
    'current_type_id' => $current_type_id,
    'post' => $post,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_type_id = (int)post_parametr('type_id');
    $error_img = '';
    $file = $_FILES['img_file']['name'] ?? '';

    switch ($current_type_id) {
        // Текст
        case 1:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'text' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, text) VALUES (1, ?, ?, ?)";
            break;
        // Цитата
        case 2:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'quote' => FILTER_DEFAULT, 'caption' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, quote, caption) VALUES (1, ?, ?, ?, ?)";
            break;
        // Фото
        case 3:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'img_url' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, img_url) VALUES (1, ?, ?, ?)";

            break;
        // Видео
        case 4:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, '' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, youtube_url) VALUES (1, ?, ?, ?)";
            break;
        // Ссылка
        case 5:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'link' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, link) VALUES (1, ?, ?, ?)";
            break;
    }

    $tag = post_parametr('tag');
    $all_input = $post;
    $all_input['tag'] = $tag;
    $errors = get_required_errors($all_input, $required);

    if ($file) {
        $tmp_name = $_FILES['img_file']['tmp_name'];
        $path = $_FILES['img_file']['name'];

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
            $post['img_file'] = $_FILES['img_file'];
            move_uploaded_file($_FILES['img_file']['tmp_name'], 'uploads/'. $filename);
        } else {
            $errors['img_file'] = 'Допустимые форматы файлов: jpg, jpeg, png, gif.';
        }
    } else {
        $errors['img_url'] = 'Вы не загрузили файл';
    }

    var_dump($post);


    $errors = array_filter($errors);

    if(count($errors)) {
        $page_content = include_template('adding-post.php', [
            'types' => $types,
            'errors' => $errors,
            'current_type_id' => $current_type_id,
            'post' => $post,
        ]);
    } else {
        $stmt = db_get_prepare_stmt($link, $sql, $post);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $post_id = mysqli_insert_id($link);

            header('Location: post.php?post_id=' . $post_id);
        }
        else {
            show_error(mysqli_error($link));
        }
    }
}

$page_header = include_template('header.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

$page_footer = include_template('footer.php', []);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
