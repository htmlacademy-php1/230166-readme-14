<?php
/**
 * Показ ошибки
 * @param string
 * @return string
*/
function show_error(&$page_content, $error):string {
    $page_content = include_template('error.php', ['error' => $error]);
}

/**
 * типы категорий
 * @param string
 * @return array
*/
function get_types():array {
    $sql = "SELECT id, name, class FROM type";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * популярные посты
 * @param string
 * @return array
*/
function get_popular_posts($link, $type_id):array {
    if ($type_id) {
        $sql = "SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.caption, p.img_url, p.youtube_url, p.link, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE type_id = $type_id
            ORDER BY p.created_at DESC LIMIT 6";
    }
    else {
        $sql = "SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.caption, p.img_url, p.youtube_url, p.link, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            ORDER BY p.created_at DESC LIMIT 6";
    }

    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Получение поста по id
 * @param string
 * @return array
*/
function get_post($link, $post_id):array
{
    $sql = "SELECT p.id post_id, p.created_at post_created_at, p.title, p.text,
                p.caption, p.img_url, p.youtube_url, p.link, p.views,
                u.id user_id, u.created_at user_created_at, u.login, u.avatar,
                t.id type_id, t.name type_name, t.class type_class
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.id = $post_id";

    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Комментарии
 * @param string
 * @return array
*/
function get_comments($link, $post_id): array
{
    $sql = "SELECT c.*, u.login author, u.avatar FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = $post_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);;
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Хэштеги
 * @param string
 * @return array
*/
function get_hashtags($link, $post_id): array
{
    $sql = "SELECT h.* FROM hashtag h
            JOIN post_hashtag ph ON ph.hashtag_id = h.id
            JOIN post p ON p.id = ph.post_id
            WHERE ph.post_id = $post_id
            GROUP BY ph.hashtag_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);;
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Количество лайков
 * @param string
 * @return int
*/
function get_count_favs($link, $post_id): int
{
    $sql = "SELECT COUNT(id) AS count FROM fav WHERE post_id = $post_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Количество коментариев
 * @param string
 * @return int
*/
function get_count_comments($link, $post_id): int
{
    $sql = "SELECT COUNT(id) AS count FROM comment WHERE post_id = $post_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Количество постов пользователя
 * @param string
 * @return int
*/
function get_count_posts($link, $user_id): int
{
    $sql = "SELECT COUNT(id) AS count FROM post WHERE user_id = $user_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error($page_content, mysqli_error($link));
}

/**
 * Количество подписчиков пользователя
 * @param string
 * @return int
*/
function get_count_subscribers($link, $user_id):int
{
    $sql = "SELECT COUNT(id) AS count FROM subscribe WHERE user_id_publisher = $user_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error($page_content, mysqli_error($link));
}

