<?php
require_once 'config/init.php';

$page_title = 'readme: регистрация';

$page_content = include_template('registration.php', [

]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(count($errors)) {
        $page_content = include_template('registration.php', [

        ]);
    } else {
        $stmt = db_get_prepare_stmt($con, $sql, $new_comment);
        $result = mysqli_stmt_execute($stmt);

        // header('Location: post.php?post_id=' . $post_id);

        if (!$result) {
            show_error(mysqli_error($con));
        }
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
