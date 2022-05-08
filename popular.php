<?php
require_once 'config/init.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: популярное';
$type_id = filter_get_parametr('type_id');

$types = get_all_types($con);

// Валидация типа контента
if ($type_id && check_id($types, $type_id)) {
    show_error("Такая категория пока не создана.");
}

$page_content = include_template('popular.php', [
    'popular_posts' => get_popular_posts($con, $type_id),
    'types' => get_all_types($con)
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'user' => $user,
    'page_content' => $page_content
]);

print($page_layout);
