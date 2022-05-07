<?php
require_once 'config/init.php';

$page_title = 'readme: публикация';

$page_layout = include_template('page-layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => include_template('feed.php', [

    ])
]);

$layout_content = include_template('layout.php', [
    'page_layout' => $page_layout,
    'page_title' => $page_title
]);

print($layout_content);
