<?php

require_once 'config/init.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: публикация';
$type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_NUMBER_INT);
$types = get_all_types($con);

// Валидация типа контента
if (check_id($types, $type_id)) {
    show_error("Такая категория пока не создана.");
}

$page_content = include_template('feed.php', [
    'types' => $types,
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'user' => $user,
    'page_content' => $page_content
]);

print($page_layout);
