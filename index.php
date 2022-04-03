
<?php
require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';

$page_title = 'readme: популярное';
$page_content = include_template('main.php', ['popular_posts' => $popular_posts]);
$layout_content = include_template('layout.php', [
        'page_title' => $page_title,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'page_content' => $page_content,
    ]
);

print($layout_content);
