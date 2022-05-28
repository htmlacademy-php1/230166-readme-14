<?php
require_once 'config/init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: index.php');
    exit();
}

$page_title = 'readme: результаты поиска';
$current_page = $_SERVER['HTTP_REFERER'];
$current_user_id = (int)$current_user['id'];
$search = trim(filter_input(INPUT_GET, 'search')) ?? '';

$search_results = [];

if ($search) {

    $search_results = get_search_results($con, $search, $current_user_id) ?? null;

    if ($search_results) {
        $page_content = include_template('search-results.php', [
            'search' => $search,
            'posts' => $search_results
        ]);
    } else {
        $page_content = include_template('no-search-results.php', [
            'search' => $search,
            'current_page' => $current_page
        ]);
    }

    $page_layout = include_template('page-layout.php', [
        'page_title' => $page_title,
        'current_user' => $current_user,
        'page_content' => $page_content
    ]);

    print($page_layout);

} else {
    header('Location: ' . $current_page);
}
