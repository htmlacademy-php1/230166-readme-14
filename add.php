<?php
require_once 'config/init.php';

$page_title = 'readme: добавление публикации';
$types = get_types($link);
$types_id = array_column($types, 'id');
$current_type_id = (int)get_parametr('type_id');
$errors = [];
$post = [];
$tag = NULL;

$required = [
    'title' => 'Заголовок',
    'text' => 'Текст поста',
    'tag' => 'Тэги',
    'link' => 'Ссылка',
    'quote' => 'Текст цитаты',
    'caption' => 'Автор',
    'youtube_url' => 'Ссылка youtube'
];

$page_content = include_template('adding-post.php', [
    'types' => $types,
    'errors' => $errors,
    'current_type_id' => $current_type_id,
    'post' => $post,
    'tag' => $tag
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_type_id = (int)post_parametr('type_id');

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

    $file_name = $_FILES['img_file']['name'] ?? NULL;

    if ($file_name) {
        var_dump($_FILES);
        $tmp_name = $_FILES['img_file']['tmp_name'];

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
            move_uploaded_file($_FILES['img_file']['tmp_name'], 'uploads/'. $filename);
            $post['img_url'] = "uploads/". $filename;
        } else {
            // Валидация поля «Выбор файла»
            $errors['img_url'] = 'Допустимые форматы файлов: jpg, jpeg, png, gif.';
        }
    }

    // Валидация записи типа «Картинка»
    if (!$file_name && isset($post['img_url'])) {
        $errors['img_url'] = 'Вы не загрузили файл';
    }

    // Получение тегов из массива POST, обрезаем пробелы вначале и в конце, и переводим в безопасные символы
    $tag = trim(esc(post_parametr('tag')));
    // Объединение в один массив данные для поста и тэгов для проверки валидации на обязательные поля
    $all_input = $post;
    $all_input['tag'] = $tag;

    // Получение ошибок для обязательных полей
    $required_errors = get_required_errors($all_input, $required);

    // Валидация поля «Ссылка из интернета»
    $url = $post['link'] ?? NULL;
    $errors['link'] = validate_url($url);

    var_dump($errors);

    // Объеденение ошибок
    $errors = array_merge($errors, $required_errors);

    // Удаление из ошибок пустых значений
    $errors = array_filter($errors);

    if(count($errors)) {
        $page_content = include_template('adding-post.php', [
            'types' => $types,
            'errors' => $errors,
            'current_type_id' => $current_type_id,
            'post' => $post,
            'tag' => $tag
        ]);
    } else {
        $stmt = db_get_prepare_stmt($link, $sql, $post);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $post_id = mysqli_insert_id($link);

            // Привязка тегов к публикации
            $tags = explode(' ', $tag);

            foreach ($tags as $tag) {
                $sql = "SELECT * FROM tag WHERE text = '{$tag}'";
                $result = mysqli_query($link, $sql);
                $exist_tag = mysqli_fetch_assoc($result);

                if ($exist_tag) {
                    $tag_id = $exist_tag['id'];
                } else {
                    $sql = "INSERT INTO tag SET text = '{$tag}'";
                    $result = mysqli_query($link, $sql);
                    $tag_id = mysqli_insert_id($link);
                }

                $sql = "INSERT INTO post_tag (post_id, tag_id) VALUES ({$post_id}, {$tag_id})";
                $result = mysqli_query($link, $sql);
            }

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
