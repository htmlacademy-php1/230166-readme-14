<?php
require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';

$page_title = 'readme: популярное';

$page_header = include_template('header.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$page_content = include_template('main.php', [
    'popular_posts' => $popular_posts,
]);

$page_footer = include_template('footer.php', []);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer
]);

print($layout_content);
