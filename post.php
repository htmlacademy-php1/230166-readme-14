<?php

require_once 'config/init.php';

$page_title = 'readme: публикация';

$post_id = intval(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT));

if (!$post_id && !check_post_id($con, $post_id)) {
    show_error('Пост будет написан в ближайшее время.');
}

$current_user_id = (int)$current_user['id'];

$post = get_post_by_id($con, $post_id, $current_user_id);

$user_id = (int) $post['user_id'];
$user = get_user_by_id($con, $user_id, $current_user_id);

$is_show_comments = filter_input(INPUT_GET, 'is_show_comments');
$comment = '';

$errors = [];

if (!isset($_SESSION["visit_count"])) {
    add_views($con, $post_id);
}

$_SESSION["visit_count"] = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = filter_input(INPUT_POST, 'comment');

   if ($comment) {
        add_comment($con, $post_id, $current_user_id, $comment);
    } else {
        $errors['comment'] = 'Это поле должно быть заполнено';
    }
}

$page_content = include_template('post-details.php', [
    'user' => $user,
    'current_user' => $current_user,
    'post' => $post,
    'is_show_comments' => $is_show_comments,
    'comment' => $comment,
    'errors' => $errors
]);

$page_layout = include_template('page-layout.php', [
    'page_title' => $page_title,
    'current_user' => $current_user,
    'page_content' => $page_content
]);

print($page_layout);
