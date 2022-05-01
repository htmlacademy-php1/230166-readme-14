<?php
require_once 'config/init.php';

$page_title = 'readme: публикация';

$post_id = (int) get_parametr('post_id');
$post = get_post($link, $post_id);
$comments = get_comments($link, $post_id);
$user_id = (int) $post['user_id'];
$is_show_comments = get_parametr('is_show_comments');

$page_content = include_template('post-details.php', [
    'post' => $post,
    'comments' => $comments,
    'comments_start' => array_slice($comments, 0, 2),
    'comments_more' => array_slice($comments, 2),
    'count_favs' => get_count_favs($link, $post_id),
    'count_posts' => get_count_posts($link, $user_id),
    'count_subscribes' => get_count_subscribers($link, $user_id),
    'count_comments' => count($comments),
    'tags' => get_tags($link, $post_id),
    'is_show_comments' => $is_show_comments
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
