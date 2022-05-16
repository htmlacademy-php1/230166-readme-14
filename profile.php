<?php

require_once 'config/init.php';

$page_title = 'readme: профиль';

$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? NULL;

if (!$user_id && !check_user_id($con, $user_id)) {
    show_error('Такой пользователь не зарегистрирован.');
}

$current_user_id = (int)$current_user['id'];
$user = get_user_by_id($con, $user_id, $current_user_id);
$user_posts = get_user_posts($con, $user_id, $current_user_id);
$posts= [];

foreach ($user_posts as $post) {
    $post['current_user'] = $current_user;
    $post['is_show_comments'] = filter_input(INPUT_GET, 'is_show_comments') ?? NULL;
    $post['post_id'] = filter_input(INPUT_GET, 'post_id') ?? NULL;
    $post['form_url'] = 'profile.php?user_id=' . $user_id . '&post_id=' . $post['id'];
    $post['errors'] = '';
    $post['comment'] = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($post['id'] === $post['post_id']) {
            $post['comment'] = filter_input(INPUT_POST, 'comment');

            if ($post['comment']) {
                add_comment($con, $post['id'], $current_user_id, $post['comment']);
            } else {
                $post['errors'] = 'Это поле должно быть заполнено';
            }
        }
    }

    $posts[] = $post;
}

$page_content = include_template('profile.php', [
    'user' => $user,
    'current_user' => $current_user,
    'posts' => $posts,
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
