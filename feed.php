<?php

require_once 'config/init.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: публикация';
$type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_NUMBER_INT) ?? NULL;
$types = get_all_types($con);


// Валидация типа контента
if ($type_id && check_id($types, $type_id)) {
    show_error("Такая категория пока не создана.");
}

$publishers = get_user_id_publishers($con, 1);
$publisher_ids = array_column($publishers, 'user_id_publisher');
$feed_posts = get_feed_posts($con, $publisher_ids);

$page_content = include_template('feed.php', [
    'type_id' => $type_id,
    'types' => $types,
    'posts' => $feed_posts
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'user' => $user,
    'page_content' => $page_content
]);

print($page_layout);
