<?php
require_once 'config/init.php';

$page_title = 'readme: добавление публикации';
$types = get_types($link);
$types_id = array_column($types, "id");
$errors = [];
$required = ['title' => 'Заголовок', 'text' => 'Текст', 'tag' => 'Тэги', 'link' => 'Ссылка', 'quote' => 'Текст цитаты', 'youtube_url' => 'Ссылка youtube'];
$current_type_id = (int)get_parametr('type_id');
$post = [];

$page_content = include_template('adding-post.php', [
    'types' => $types,
    'errors' => $errors,
    'current_type_id' => $current_type_id,
    'post' => $post
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_type_id = (int)post_parametr('type_id');
    $error_img = '';


    switch ($current_type_id) {
        // Текст
        case 1:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'text' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, text) VALUES (1, ?, ?, ?)";
            break;
        // Цитата
        case 2:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'quote' => FILTER_DEFAULT, 'caption' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, quote, caption) VALUES (1, ?, ?, ?)";
            break;
        // Фото
        case 3:
            if (post_parametr('img_file')) {
                $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'img_file' => FILTER_DEFAULT], true);
                $sql = "INSERT INTO post (user_id, type_id, title, img_file) VALUES (1, ?, ?, ?)";
            } else {
                $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'img_url' => FILTER_DEFAULT], true);
                $sql = "INSERT INTO post (user_id, type_id, title, img_url) VALUES (1, ?, ?, ?)";
            }

            if (is_null(post_parametr('img_file')) && is_null(post_parametr('img_url'))) {
                $error_img = 'Загрузите фото';
            }

            break;
        // Видео
        case 4:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'youtube_url' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, text) VALUES (1, ?, ?, ?)";
            break;
        // Ссылка
        case 5:
            $post = filter_input_array(INPUT_POST, ['type_id' => FILTER_DEFAULT, 'title' => FILTER_DEFAULT, 'link' => FILTER_DEFAULT], true);
            $sql = "INSERT INTO post (user_id, type_id, title, link) VALUES (1, ?, ?, ?)";
            break;
    }

    var_dump(post_parametr('img_url'));


    $tag = post_parametr('tag');
    $all_input = $post;
    $all_input['tag'] = $tag;
    $errors = get_required_errors($all_input, $required);

    $errors['img'] = $error_img;
    $errors = array_filter($errors);
    // var_dump($errors);
    var_dump($post);

    if(count($errors)) {
        var_dump($current_type_id);
        $page_content = include_template('adding-post.php', [
            'types' => $types,
            'errors' => $errors,
            'current_type_id' => $current_type_id,
            'post' => $post
        ]);

        // header("Location: add.php?type_id=" . $current_type_id);
    } else {
        $stmt = db_get_prepare_stmt($link, $sql, $post);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $post_id = mysqli_insert_id($link);

            header("Location: post.php?post_id=" . $post_id);
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
