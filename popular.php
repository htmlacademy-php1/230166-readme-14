<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: популярное';

$type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_NUMBER_INT) ?? NULL;
$types = get_all_types($con);

if ($type_id && check_id($types, $type_id)) {
    show_error("Такая категория пока не создана.");
}

$current_page = filter_input(INPUT_GET, 'page') ?? 1;
$page_items = 6;
$items_count = get_count_all_posts($con);
$pages_count = ceil($items_count / $page_items);
$offset = ($current_page - 1) * $page_items;
$pages = range(1, $pages_count);
$posts_new = [];

$posts = get_popular_posts($con, $page_items, $offset, $current_user['id'], $type_id);

$page_content = include_template('popular.php', [
    'posts' => $posts,
    'types' => $types,
    'type_id' => $type_id,
    'current_page' => $current_page,
    'pages_count' => $pages_count,
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
