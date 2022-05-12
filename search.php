<?php
require_once 'config/init.php';

$page_title = 'readme: результаты поиска';
$current_page = $_SERVER['HTTP_REFERER'];
$search = trim(filter_input(INPUT_GET, 'search')) ?? '';
var_dump($search);
$search_results = [];

if ($search) {
    $search_results = get_search_results($con, $search);

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
        'user' => $user,
        'page_content' => $page_content
    ]);

    print($page_layout);

} else {
    header('Location: ' . $current_page);
}