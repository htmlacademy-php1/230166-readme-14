<?php
require_once 'config/init.php';

$page_title = 'readme: популярное';
$type_id = filter_get_parametr('type_id');

$page_content = include_template('popular.php', [
    'popular_posts' => get_popular_posts($con, $type_id),
    'types' => get_types($con)
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_title' => $page_title,
    'page_content' => $page_content,
]);

print($layout_content);
