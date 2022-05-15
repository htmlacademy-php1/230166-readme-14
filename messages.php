<?php

require_once 'config/init.php';

$page_title = 'readme: личные сообщения';

$page_content = include_template('messages.php', [

]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
