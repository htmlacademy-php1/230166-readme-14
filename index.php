<?php
require_once 'config/init.php';

$page_title = 'readme: популярное';
$type_id = filter_input(INPUT_GET, 'type_id');

$page_content = include_template('popular.php', [
    'popular_posts' => get_popular_posts($link, $type_id),
    'link' => $link
]);

$page_header = include_template('header.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$page_footer = include_template('footer.php', []);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
