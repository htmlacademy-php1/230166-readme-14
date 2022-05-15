<?php

require_once 'config/init.php';

$page_title = 'readme: профиль';

$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? NULL;

if (!$user_id && !check_user_id($con, $user_id)) {
    show_error('Такой пользователь не зарегистрирован.');
}

$current_user_id = (int)$current_user['id'];
$user = get_user_by_id($con, $user_id);
$posts = get_user_posts($con, $user_id, $current_user_id);

$page_content = include_template('profile.php', [
    'user' => $user,
    'posts' => $posts,
]);
// if($posts) {

// } else {
//     $page_content = include_template('no-content.php');
// }

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
