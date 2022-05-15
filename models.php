<?php

// Ошибка

/**
 * Вывод ошибки в отдельном шаблоне error.php
 * @param string $error Текст ошибки
 * @return string шаблон с выводом ошибки
*/
function show_error($error)
{
    if (http_response_code(404)) {
        $page_content = include_template('404.php', ['error' => $error]);
    } elseif (http_response_code(500)) {
        $page_content = include_template('500.php', ['error' => $error]);
    } else {
        $page_content = include_template('error.php', ['error' => $error]);
    }

    exit($page_content);
}


// Получение записей из БД

// _Пользователи
/**
 * Получение пользователя по email
 * @param mysqli $con Ресурс соединения
 * @param string $email почта пользователя
 * @return int
*/
function get_user_by_email($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $user['count_posts'] = get_count_user_posts($con, $user['id']);
        $user['count_subscribes'] = get_count_subscribers($con, $user['id']);

        return $user;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение пользователя по id
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int или NULL
*/
function get_user_by_id($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $user['count_posts'] = get_count_user_posts($con, $user['id']);
        $user['count_subscribes'] = get_count_subscribers($con, $user['id']);

        return $user;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение id юзеров на каторый подписан залогиненный пользователь
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int
*/
function get_user_id_publishers($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT user_id_publisher FROM subscribe WHERE user_id_subscriber = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}


// _Категории
/**
 * Получение всех категорий
 * @param mysqli $con Ресурс соединения
 * @return array or string
*/
function get_all_types($con)
{
    $sql = "SELECT * FROM type";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}

/**
 * Получение категории по id
 * @param mysqli $con Ресурс соединения
 * @param int $type_id
 * @return array название типа, его id, класс и размеры для иконок
*/
function get_type($con, $type_id)
{
    $sql = "SELECT * FROM type WHERE id = " . (int)$type_id;
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}


// _Посты

/**
 * получение всех постов или из одной категории
 * @param mysqli $con Ресурс соединения
 * @param int $type_id по типу
 * @return array
*/
function get_all_posts($con, $current_user_id, $type_id = NULL)
{
    if (!$type_id) {
        $sql = "SELECT p.id, p.created_at, p.user_id, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            ORDER BY p.created_at";
    } else {
        $sql = "SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.quote, p.text, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE type_id = $type_id
            ORDER BY p.created_at";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_favs'] = get_count_favs($con, $post_id);
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error(mysqli_error($con));
}

/**
 * получение всех популярных постов или одной категории
 * @param mysqli $con Ресурс соединения
 * @param int $type_id категория
 * @return array
*/
function get_popular_posts($con, $page_items, $offset, $current_user_id, $type_id = NULL)
{
    if (!$type_id) {
        $sql = "SELECT p.id, p.created_at, p.user_id, u.login, u.avatar, t.id type_id,
                    t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url,
                    p.video_url, p.link_url, p.views
                FROM post p
                JOIN user u ON p.user_id = u.id
                JOIN type t ON p.type_id = t.id
                ORDER BY p.views DESC
                LIMIT $page_items
                OFFSET $offset";
    } else {
        $sql = "SELECT p.id, p.created_at, p.user_id, u.login, u.avatar, t.id type_id,
                    t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url,
                    p.video_url, p.link_url, p.views
                FROM post p
                JOIN user u ON p.user_id = u.id
                JOIN type t ON p.type_id = t.id
                WHERE type_id = $type_id
                ORDER BY p.views DESC
                LIMIT $page_items
                OFFSET $offset";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_favs'] = get_count_favs($con, $post_id);
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение одного поста по id поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id для получения поста
 * @param int $user_id для добавления поля добавлен ли этот пост в избранное текущего пользователя
 * @return array
*/
function get_post_by_id($con, $post_id, $current_user_id)
{
    $sql = "SELECT p.id, p.created_at post_created_at, p.title, p.text,
                p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views,
                u.id user_id, u.created_at user_created_at, u.login, u.avatar,
                t.id type_id, t.name type_name, t.class type_class
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.id = " . (int)$post_id;

    $result = mysqli_query($con, $sql);

    if ($result) {
        $post =  mysqli_fetch_assoc($result);
        $post['count_favs'] = get_count_favs($con, $post_id);
        $post['count_comments'] = get_count_comments($con, $post_id);
        $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);

        return $post;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение постов одного пользователя по id пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id id пользователя
 * @return array
*/
function get_user_posts($con, $user_id, $current_user_id)
{
    $sql = "SELECT p.id, p.created_at post_created_at, p.title, p.text,
                p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views,
                u.id user_id, u.created_at user_created_at, u.login, u.avatar,
                t.id type_id, t.name type_name, t.class type_class
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.user_id = " . (int)$user_id;

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_favs'] = get_count_favs($con, $post_id);
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение постов на которые подписан юзер
 * @param mysqli $con Ресурс соединения
 * @param array $publishers id юзеров на которые подписан пользователь
 * @return int
*/
function get_feed_posts($con, $publishers, $current_user_id)
{
    $posts = get_all_posts($con, $current_user_id);
    $feed_posts = [];

    foreach($posts as $key => $post) {
        foreach($publishers as $publisher) {
            if ($post['user_id'] === $publisher) {
                $feed_posts[] = $post;
            }
        }
    }

    return $feed_posts;
}

/**
 * Получение постов для результатов поиска по хэштэгу и строке
 * @param mysqli $con Ресурс соединения
 * @param string $search хэштег или строка поиска
 * @return array, null
*/
function get_search_results($con, $search)
{
    if (mb_substr($search, 0, 1) !== '#') {
        $sql = "SELECT p.id, p.created_at, p.user_id, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
                JOIN user u ON p.user_id = u.id
                JOIN type t ON p.type_id = t.id
                WHERE MATCH(p.title, p.text) AGAINST(?)
                ORDER BY p.created_at";
        $stmt = db_get_prepare_stmt($con, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $tag_id = get_tag_id($con, $search);

        if ($tag_id) {
            $sql = "SELECT p.id, p.created_at, p.user_id, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            JOIN post_tag pt ON pt.post_id = p.id
            WHERE pt.tag_id = $tag_id";

            $result = mysqli_query($con, $sql);
        } else {
            return NULL;
        }
    }

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return NULL;
}


// _Комментарии
/**
 * Получение комментариев к посту
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return array
*/
function get_comments($con, $post_id)
{
    $sql = "SELECT c.*, u.id user_id, u.login, u.avatar FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = $post_id
            ORDER BY c.created_at DESC";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}


// _Хэштэги
/**
 * Получение хэштегов для поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return array
*/
function get_tags($con, $post_id)
{
    $sql = "SELECT h.* FROM tag h
            JOIN post_tag ph ON ph.tag_id = h.id
            JOIN post p ON p.id = ph.post_id
            WHERE ph.post_id = $post_id
            GROUP BY ph.tag_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}

/**
 * Получение id хэштега по тексту
 * @param mysqli $con Ресурс соединения
 * @param string текст хэштега
 * @return int
*/
function get_tag_id($con, $tag)
{
    $tag = mysqli_real_escape_string($con, $tag);
    $sql = "SELECT id FROM tag WHERE text = '{$tag}'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['id'];
    }

    return NULL;
}


// Проверки

/**
 * Проверка email пользователя
 * @param mysqli $con Ресурс соединения
 * @param string $email почта пользователя
 * @return int
*/
function check_user_email($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $email = mysqli_fetch_assoc($result);

        if ($email) {
            return true;
        }
        return false;
    }

    show_error(mysqli_error($con));
}

/**
 * Проверка логина пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $login
 * @return int
*/
function check_user_login($con, $login)
{
    $login = mysqli_real_escape_string($con, $login);
    $sql = "SELECT login FROM user WHERE login = '$login'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $login = mysqli_fetch_assoc($result);

        if ($login) {
            return true;
        }
        return false;
    }

    show_error(mysqli_error($con));
}

/**
 * Проверка id пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id id пользователя
 * @return int или NULL
*/
function check_user_id($con, $user_id)
{
    $login = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $user_id = mysqli_fetch_assoc($result);

        if ($user_id) {
            return true;
        }
        return false;
    }

    show_error(mysqli_error($con));
}

/**
 * Проверка id поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id id поста
 * @return int или NULL
*/
function check_post_id($con, $post_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT id FROM post WHERE id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $post_id = mysqli_fetch_assoc($result);

        if ($post_id) {
            return true;
        }
        return false;
    }

    show_error(mysqli_error($con));
}

/**
 * Проверка. Добавлен ли пост в избранное
 * @param mysqli $con Ресурс соединения
 * @return bool
*/
function check_is_fav($con, $post_id, $user_id)
{
    $sql = "SELECT id FROM fav WHERE user_id = $user_id AND post_id = {$post_id}";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $fav = mysqli_fetch_assoc($result);

        if ($fav) {
            return true;
        }
        return false;
    }

    show_error(mysqli_error($con));
}


// Количество записей в БД

/**
 * Количество всех постов в БД
 * @param mysqli $con Ресурс соединения
 * @return int
*/
function get_count_all_posts($con)
{
    $sql = "SELECT COUNT(*) as count FROM post";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Количество подписчиков пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int
*/
function get_count_subscribers($con, $user_id)
{
    $sql = "SELECT COUNT(id) AS count FROM subscribe WHERE user_id_publisher = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Количество лайков
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return int
*/
function get_count_favs($con, $post_id)
{
    $sql = "SELECT COUNT(id) AS count FROM fav WHERE post_id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Количество коментариев
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return int
*/
function get_count_comments($con, $post_id)
{
    $sql = "SELECT COUNT(id) AS count FROM comment WHERE post_id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Количество постов пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int
*/
function get_count_user_posts($con, $user_id)
{
    $sql = "SELECT COUNT(id) AS count FROM post WHERE user_id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}


// Добавление новой записи в БД

/**
 * Добавление просмотра поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return string если ошибка, а так ничего не возвращает
*/
function increase_views($con, $post_id)
{
    $sql = "UPDATE post SET views = views + 1 WHERE id = $post_id";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error(mysqli_error($con));
    }
}
