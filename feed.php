<?php

require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: публикация';
$type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
$types = get_all_types($con);

if ($type_id && check_id($types, $type_id)) {
    show_error("Такая категория пока не создана.");
}

$current_user_id = (int)$current_user['id'];

$publishers = get_user_id_publishers($con, $current_user_id);

if($publishers) {
    $publisher_ids = array_column($publishers, 'user_id_publisher');
    $posts = get_feed_posts($con, $publisher_ids, $current_user_id, $type_id);

    $page_content = include_template('feed.php', [
        'type_id' => $type_id,
        'types' => $types,
        'posts' => $posts
    ]);
} else {
    $page_content = include_template('no-content.php');
}

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
