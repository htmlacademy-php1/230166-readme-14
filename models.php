<?php
/**
 * Вывод ошибки в отдельном шаблоне error.php
 * @param string текст ошибки
 * @return string шаблон с выводом ошибки
*/
function show_error($error) {
    $page_content = include_template('error.php', ['error' => $error]);
    exit($page_content);
}

/**
 * Получение данных из массива GET
 * @param string $name
 * @return mixed
*/
function filter_get_parametr($name) {
    return filter_input(INPUT_GET, $name);
}

/**
 * Получение данных из массива POST
 * @param string $name
 * @return mixed
*/
function filter_post_parametr($name) {
    return filter_input(INPUT_POST, $name);
}

/**
 * Получение всех типов контента для постов
 * @param mysqli $con Ресурс соединения
 * @return array or string
*/
function get_types($con) {
    $sql = "SELECT * FROM type";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}

/**
 * Получение одного типа контента по id
 * @param mysqli $con Ресурс соединения
 * @param int $type_id
 * @return array название типа, его id, класс и размеры для иконок
*/
function get_type($con, $type_id) {
    $sql = "SELECT * FROM type WHERE id = " . (int)$type_id;
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}

/**
 * популярные посты
 * @param mysqli $con Ресурс соединения
 * @param int $type_id
 * @return array
*/
function get_popular_posts($con, $type_id) {
    if ($type_id) {
        $sql = "SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.quote, p.text, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE type_id =" . (int)$type_id .
            " ORDER BY p.created_at DESC LIMIT 6";
    }
    else {
        $sql = "SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            ORDER BY p.created_at DESC LIMIT 6";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $popular_posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_favs'] = get_count_favs($con, $post_id);
            $post['count_comments'] = get_count_comments($con, $post_id);
            $popular_posts[] = $post;
        }

        return $popular_posts;
    }

    show_error(mysqli_error($con));
}

/**
 * Получение поста по id
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return array
*/
function get_post(object $con, int $post_id) {
    $sql = "SELECT p.id post_id, p.created_at post_created_at, p.title, p.text,
                p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views,
                u.id user_id, u.created_at user_created_at, u.login, u.avatar,
                t.id type_id, t.name type_name, t.class type_class
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.id = " . (int)$post_id;

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    show_error(mysqli_error($con));
}

/**
 * Комментарии
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return array
*/
function get_comments($con, $post_id) {
    $sql = "SELECT c.*, u.login author, u.avatar FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);;
    }

    show_error(mysqli_error($con));
}

/**
 * Хэштеги
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return array
*/
function get_tags($con, $post_id) {
    $sql = "SELECT h.* FROM tag h
            JOIN post_tag ph ON ph.tag_id = h.id
            JOIN post p ON p.id = ph.post_id
            WHERE ph.post_id = $post_id
            GROUP BY ph.tag_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);;
    }

    show_error(mysqli_error($con));
}

/**
 * Количество лайков
 * @param mysqli $con Ресурс соединения
 * @param int $post_id
 * @return int
*/
function get_count_favs($con, $post_id) {
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
function get_count_comments($con, $post_id) {
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
function get_count_posts($con, $user_id) {
    $sql = "SELECT COUNT(id) AS count FROM post WHERE user_id = $user_id";
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
function get_count_subscribers($con, $user_id) {
    $sql = "SELECT COUNT(id) AS count FROM subscribe WHERE user_id_publisher = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Проверка на существование пользователя по email
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int
*/
function check_user_email($con, $email) {
    $sql = "SELECT id FROM user WHERE email = '{$email}'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    return NULL;
}

/**
 * Проверка на существование пользователя по логину
 * @param mysqli $con Ресурс соединения
 * @param int $user_id
 * @return int
*/
function check_user_login($con, $login) {
    $sql = "SELECT id FROM user WHERE login = '{$login}'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    return NULL;
}
