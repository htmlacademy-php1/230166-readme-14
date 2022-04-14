<?php
require_once 'config/init.php';

$page_title = 'readme: популярное';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $sql = "SELECT id, name, class FROM type";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $types = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }

    $sql = "SELECT p.created_at, u.login, u.avatar, t.name, t.class, p.title, p.text, p.caption, p.img, p.video, p.link, p.views FROM post p JOIN user u ON p.user_id = u.id JOIN type t ON p.type_id = t.id ORDER BY p.views DESC";
    $res = mysqli_query($link, $sql);

    if ($res = mysqli_query($link, $sql)) {
        $popular_posts = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $page_content = include_template('main.php', [
            'popular_posts' => $popular_posts,
        ]);
    }
    else {
        $content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
}

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
