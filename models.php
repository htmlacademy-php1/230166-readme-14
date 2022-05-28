<?php

/**
 * Функция проверяет код состояния ответа, и возвращает один из
 * трёх шаблонов с текстом ошибки, для 404, для 500 и для остальных
 *
 * @param  string - текст ошибки
 * @return string
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
 * Функция принимает email пользователя и возвращает запись
 * пользователя из БД
 *
 * @param  mysqli $con - ресурс соединения
 * @param  string - почта
 * @return array
*/
function get_сurrent_user($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $user['count_posts'] = get_count_user_posts($con, $user['id']);
            $user['count_subscribes'] = get_count_subscribers($con, $user['id']);

            return $user;
        } else {
            return null;
        }
    }

    show_error('get_сurrent_user ' . mysqli_error($con));
}

/**
 * Получение пользователя по id
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id - id пользователя
 * @param  int $current_user_id - id текущего пользователя
 * @return array
*/
function get_all_user_data($con, $user_id, $current_user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $user['count_posts'] = get_count_user_posts($con, $user_id);
        $user['count_subscribes'] = get_count_subscribers($con, $user_id);
        $user['is_subscribe'] = check_subscribe($con, $user_id, $current_user_id);

        return $user;
    }

    show_error('get_all_user_data ' . mysqli_error($con));
}

/**
 * Функция принимает id пользователя и возвращает список id пользователей
 * на которые он подписан
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int - id пользователя
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

    show_error('get_user_id_publishers' . mysqli_error($con));
}


// _Категории

/**
 * Получение всех категорий
 *
 * @param  mysqli $con - ресурс соединения
 * @return array
*/
function get_all_types($con)
{
    $sql = "SELECT * FROM type";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_all_types ' . mysqli_error($con));
}

/**
 * Получение определенной категории из БД по id
 *
 * @param mysqli $con - ресурс соединения
 * @param int - id категории
 * @return array - запись из БД
*/
function get_type($con, $type_id)
{
    $type_id = mysqli_real_escape_string($con, $type_id);
    $sql = "SELECT * FROM type WHERE id = $type_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_type ' . mysqli_error($con));
}


// _Посты

/**
 * Получение всех постов или только для одной категории,
 * если был получен id категории
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $current_user_id - id текущего пользователя для получения дополнительных
 *            данных, например добавлен ли пост в избранное
 * @param  int $type_id - id категории, по умолчанию ровен null
 * @return array
*/
function get_all_posts($con, $current_user_id, $type_id = null)
{
    if (!$type_id) {
        $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id,
                        p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views,
                        p.repost_count, (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            ORDER BY p.created_at";
    } else {
        $type_id = mysqli_real_escape_string($con, $type_id);
        $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id,
                        p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views,
                        p.repost_count, (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
            FROM post p
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
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $post['tags'] = get_tags($con, $post_id);
            $post['comments'] = get_comments($con, $post_id);
            $post['repost'] = get_repost($con, $post_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error('get_all_posts ' . mysqli_error($con));
}

/**
 * Получение постранично отсортированных всех популярных постов или постов
 * только для одной категории, если был передан id категории
 *
 * @param mysqli $con - ресурс соединения
 * @param int $page_items - количество страниц
 * @param int $offset - количество постов на одной странице
 * @param int $current_user_id - id текущего пользователя
 * @param string $sorting - сортировка, по количеству просмотров, по количеству лайков или по дате
 * @param int $type_id - id категории, по умолчанию ровен null
 * @return array
*/
function get_popular_posts($con, $page_items, $offset, $current_user_id, $sorting, $type_id = null)
{
    $page_items = mysqli_real_escape_string($con, $page_items);
    $offset = mysqli_real_escape_string($con, $offset);

    if (!$type_id) {
        $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id, p.title,
                        p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views, p.repost_count,
                        (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
                FROM post p
                JOIN user u ON p.user_id = u.id
                JOIN type t ON p.type_id = t.id
                ORDER BY $sorting DESC
                LIMIT $page_items
                OFFSET $offset";
    } else {
        $type_id = mysqli_real_escape_string($con, $type_id);
        $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id, p.title,
                        p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views, p.repost_count,
                        (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
                FROM post p
                JOIN user u ON p.user_id = u.id
                JOIN type t ON p.type_id = t.id
                WHERE type_id = $type_id
                ORDER BY $sorting DESC
                LIMIT $page_items
                OFFSET $offset";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error('get_popular_posts' . mysqli_error($con));
}

/**
 * Получение одного поста по id
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id текущего пользователя
 * @return array
*/
function get_post_by_id($con, $post_id, $current_user_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT u.created_at user_created_at, u.login, u.avatar, t.class, t.name, p.id, p.created_at post_created_at, p.user_id, p.type_id,
                    p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views, p.repost_count,
                    (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.id = $post_id";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $post =  mysqli_fetch_assoc($result);
        $post['count_comments'] = get_count_comments($con, $post_id);
        $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
        $post['tags'] = get_tags($con, $post_id);
        $post['comments'] = get_comments($con, $post_id);
        $post['repost'] = get_repost($con, $post_id);

        return $post;
    }

    show_error('get_post_by_id ' . mysqli_error($con));
}

/**
 * Получение постов определенного пользователя по id пользователя
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id id пользователя
 * @param  int $current_user_id - id текущего пользователя
 * @return array
*/
function get_user_posts($con, $user_id, $current_user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id, p.title, p.text,
                    p.quote, p.caption, p.photo_url, p.video_url, p.link_url, p.views, p.repost_count,
                    (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            WHERE p.user_id = $user_id
            ORDER BY p.created_at DESC";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $post['tags'] = get_tags($con, $post_id);
            $post['comments'] = get_comments($con, $post_id);
            $post['repost'] = get_repost($con, $post_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error('get_user_posts ' . mysqli_error($con));
}

/**
 * Получение постов на которые подписан пользователь
 *
 * @param  mysqli $con - ресурс соединения
 * @param  array $publishers - id пользователей на которые подписан пользователь
 * @param  int $current_user_id - id текущего пользователя
 * @param  int $type_id - id категории
 * @return array
*/
function get_feed_posts($con, $publishers, $current_user_id, $type_id)
{
    $posts = get_all_posts($con, $current_user_id, $type_id);
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
 * Получение результатов поиска, по хэштэгу или строке
 *
 * @param  mysqli $con - Ресурс соединения
 * @param  string $search - хэштег или строка поиска
 * @param  int $current_user_id
 * @return array
*/
function get_search_results($con, $search, $current_user_id)
{
    if (mb_substr($search, 0, 1) !== '#') {
        $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id,
                        p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url,
                        p.views, p.repost_count,
                        (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
                FROM post p
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
            $sql = "SELECT u.login, u.avatar, t.class, t.name, p.id, p.created_at, p.user_id, p.type_id,
                            p.title, p.text, p.quote, p.caption, p.photo_url, p.video_url, p.link_url,
                            p.views, p.repost_count,
                            (SELECT COUNT(id) FROM fav WHERE post_id = p.id) count_favs
            FROM post p
            JOIN user u ON p.user_id = u.id
            JOIN type t ON p.type_id = t.id
            JOIN post_tag pt ON pt.post_id = p.id
            WHERE pt.tag_id = $tag_id";

            $result = mysqli_query($con, $sql);
        } else {
            return null;
        }
    }

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $posts = [];

        foreach($arr as $post) {
            $post_id = $post['id'];
            $post['count_comments'] = get_count_comments($con, $post_id);
            $post['is_fav'] = check_is_fav($con, $post['id'], $current_user_id);
            $post['tags'] = get_tags($con, $post_id);
            $post['comments'] = get_comments($con, $post_id);
            $post['repost'] = get_repost($con, $post_id);
            $posts[] = $post;
        }

        return $posts;
    }

    show_error('get_search_results ' . mysqli_error($con));
}

/**
 * Функция принимает id поста, находит id исходного поста, если этот пост
 * был репостнут и возвращает автора исходного поста
 *
 * @param  mysqli $con - Ресурс соединения
 * @param  int - id поста
 * @return array
 */
function get_repost($con, $post_id) {
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT repost_post_id FROM post WHERE id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $repost_post_id = mysqli_fetch_assoc($result)['repost_post_id'];

        if ($repost_post_id) {
            $sql = "SELECT u.id user_id, u.login, u.avatar FROM user u
                    JOIN post p ON p.id = $repost_post_id
                    WHERE u.id = p.user_id";
            $result = mysqli_query($con, $sql);

            if ($result) {
                return mysqli_fetch_assoc($result);
            }

            show_error('get_repost_ ' . mysqli_error($con));
        } else {
            return null;
        }
    }

    show_error('get_repost ' . mysqli_error($con));
}

// Лайки

/**
 * Получение лайков пользователя, пользователя, категории,
 * и ссылок на материала поста для превьюшки
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int - id пользователя
 * @return array
 */
function get_favs($con, $user_id) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT f.created_at, u.id user_id, u.login, u.avatar, p.id post_id, p.photo_url, p.video_url, p.type_id
            FROM fav f
            JOIN user u ON f.user_id = u.id
            JOIN post p ON f.post_id = p.id
            WHERE p.user_id = $user_id";

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_favs ' . mysqli_error($con));
}

// Подписки

/**
 * Получение списка подписанных пользователей,
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id id пользователя
 * @param  int $current_user_id - id текущего пользователя
 * @return array
 */
function get_subscrubers($con, $user_id, $current_user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT s.created_at, s.user_id_subscriber id, u.login, u.avatar
            FROM subscribe s
            JOIN user u ON s.user_id_subscriber = u.id
            WHERE s.user_id_publisher = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $subscribers = [];

        foreach($arr as $subscriber) {
            $user_id = $subscriber['id'];
            $subscriber['count_subscribers'] = get_count_subscribers($con, $user_id);
            $subscriber['count_posts'] = get_count_user_posts($con, $user_id);
            $subscriber['is_subscribe'] = check_subscribe($con, $user_id, $current_user_id);
            $subscribers[] = $subscriber;
        }

        return $subscribers;
    }

    show_error('get_favs ' . mysqli_error($con));
}

// _Комментарии

/**
 * Получение комментариев к посту
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int - id поста
 * @return array
*/
function get_comments($con, $post_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT c.*, u.id user_id, u.login, u.avatar FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = $post_id
            ORDER BY c.created_at DESC";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_comments ' . mysqli_error($con));
}

// _Сообщения

/**
 * Получение id пользователей которым были отправлены сообщения
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $current_user_id - id текущего пользователя
 * @return array
 */
function get_ids_recipient($con, $current_user_id) {
    $current_user_id = mysqli_real_escape_string($con, $current_user_id);
    $sql = "SELECT user_id_recipient FROM message
            WHERE user_id_sender = $current_user_id
            GROUP BY user_id_recipient";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_ids_recipient ' . mysqli_error($con));
}


/**
 * Получение id пользователей которые отправили сообщения
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $current_user_id - id текущего пользователя
 * @return array
 */
function get_ids_sender($con, $current_user_id) {
    $current_user_id = mysqli_real_escape_string($con, $current_user_id);
    $sql = "SELECT user_id_sender FROM message
            WHERE user_id_recipient = $current_user_id
            GROUP BY user_id_sender";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_ids_sender ' . mysqli_error($con));
}

/**
 * Получение последнего сообщения для превью в сообщениях
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_sender
 * @param  int $user_id_recipient
 * @return array
 */
function get_last_message($con, $user_id_sender, $user_id_recipient) {
    $user_id_sender = mysqli_real_escape_string($con, $user_id_sender);
    $user_id_recipient = mysqli_real_escape_string($con, $user_id_recipient);

    $sql = "SELECT m.created_at, u.id, u.login, m.text FROM message m
            JOIN user u ON m.user_id_sender = u.id
            WHERE (m.user_id_sender = $user_id_sender AND m.user_id_recipient = $user_id_recipient)
            OR (m.user_id_sender = $user_id_recipient AND m.user_id_recipient = $user_id_sender)
            ORDER BY m.created_at DESC LIMIT 1";

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    show_error('get_last_message ' . mysqli_error($con));
}

/**
 * Получение одного пользователя по id для сообщений
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int - id пользователя
 * @return array
 */
function get_user($con, $user_id) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id, login, email, avatar FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    show_error('get_user ' . mysqli_error($con));
}

/**
 * Получение всех пользователей для страницы сообщений для текущего пользователя,
 * и сортировка их по дате по убыванию
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $current_user_id
 * @return array
 */
function get_all_communicate_users($con, $current_user_id) {
    $senders = array_column(get_ids_sender($con, $current_user_id), 'user_id_sender');
    $recipients = array_column(get_ids_recipient($con, $current_user_id), 'user_id_recipient');
    $ids = array_unique(array_merge($senders, $recipients));
    $users = [];
    $last_message = [];

    foreach($ids as $id) {
        $user = get_user($con, $id);
        $last_messages = get_last_message($con, $id, $current_user_id);
        $user['last_message'] = $last_messages;
        $user['count_new_messages'] = get_count_new_messages($con, $id, $current_user_id);

        $users[] = $user;
    }

    $dates = [];

    foreach($users as $key => $value){
        $dates[$key] = $value['last_message']['created_at'];
    }
    array_multisort($dates, SORT_DESC, $users);

    return $users;
}

/**
 * Получение сообщений с одним пользователем
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_sender
 * @param  int $user_id_recipient
 * @return array
 */
function get_messages($con, $user_id_sender, $user_id_recipient) {
    $user_id_sender = mysqli_real_escape_string($con, $user_id_sender);
    $user_id_recipient = mysqli_real_escape_string($con, $user_id_recipient);

    remove_new_messages($con, $user_id_sender, $user_id_recipient);

    $sql = "SELECT u.id user_id, u.login, u.avatar, m.created_at, m.text
            FROM message m
            JOIN user u ON m.user_id_sender = u.id
            WHERE
                (m.user_id_sender = $user_id_sender AND m.user_id_recipient = $user_id_recipient) OR
                (m.user_id_sender = $user_id_recipient AND m.user_id_recipient = $user_id_sender)
            ORDER BY m.created_at";

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_messages ' . mysqli_error($con));
}

// _Хэштэги

/**
 * Получение хэштегов для поста
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @return array
*/
function get_tags($con, $post_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);

    $sql = "SELECT h.* FROM tag h
            JOIN post_tag ph ON ph.tag_id = h.id
            JOIN post p ON p.id = ph.post_id
            WHERE ph.post_id = $post_id
            GROUP BY ph.tag_id";

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_tags ' . mysqli_error($con));
}

/**
 * Получение id хэштега
 *
 * @param  mysqli $con - ресурс соединения
 * @param  string $tag - хэштег
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

    return null;
}


// Количество записей в БД

/**
 * Количество всех постов в БД
 *
 * @param  mysqli - ресурс соединения
 * @return int
*/
function get_count_all_posts($con)
{
    $sql = "SELECT COUNT(*) as count FROM post";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error('get_count_all_posts' . mysqli_error($con));
}

/**
 * Количество подписчиков пользователя
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id - id пользователя
 * @return int
*/
function get_count_subscribers($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT COUNT(id) AS count FROM subscribe WHERE user_id_publisher = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error('get_count_subscribers ' . mysqli_error($con));
}

/**
 * Количество коментариев
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @return int
*/
function get_count_comments($con, $post_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT COUNT(id) AS count FROM comment WHERE post_id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error('get_count_comments ' . mysqli_error($con));
}

/**
 * Количество постов пользователя
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id - id пользователя
 * @return int
*/
function get_count_user_posts($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT COUNT(id) AS count FROM post WHERE user_id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error('get_count_user_posts ' . mysqli_error($con));
}

/**
 * Количество новых непрочитанных сообщений
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_sender - id отправителя
 * @param  int $user_id_recipient - id получателя
 * @return int
 */
function get_count_new_messages($con, $user_id_sender, $user_id_recipient)
{
    $user_id_sender = mysqli_real_escape_string($con, $user_id_sender);
    $user_id_recipient = mysqli_real_escape_string($con, $user_id_recipient);

    $sql = "SELECT COUNT(is_new) as count FROM message
                WHERE user_id_sender = $user_id_sender
                AND user_id_recipient = $user_id_recipient
                AND is_new = 1";

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error('get_count_new_messages ' . mysqli_error($con));
}

// Проверки

/**
 * Проверка на существование пользователя по email
 *
 * @param  mysqli $con - ресурс соединения
 * @param  string $email - почта пользователя
 * @return bool
*/
function check_user_email($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_user_email ' . mysqli_error($con));
}

/**
 * Проверка на существование пользователя по логину
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $login - логин пользователя
 * @return bool
*/
function check_user_login($con, $login)
{
    $login = mysqli_real_escape_string($con, $login);
    $sql = "SELECT login FROM user WHERE login = '$login'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_user_login ' . mysqli_error($con));
}

/**
 * Проверка на существование пользователя по id
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id - id пользователя
 * @return bool
*/
function check_user_id($con, $user_id)
{
    $login = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_user_id ' . mysqli_error($con));
}

/**
 * Проверка на существование поста по id
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @return bool
*/
function check_post_id($con, $post_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT id FROM post WHERE id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_post_id ' . mysqli_error($con));
}

/**
 * Проверка. Добавлен ли пост в избранное пользователя
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $user_id - id пользователя
 * @return bool
*/
function check_is_fav($con, $post_id, $user_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM fav WHERE user_id = $user_id AND post_id = {$post_id}";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_is_fav ' . mysqli_error($con));
}

/**
 * Проверка. Есть ли подписка на пользователя
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_publisher - id автора
 * @param  int $user_id_subscriber - id подписчика
 * @return bool
*/
function check_subscribe($con, $user_id_publisher, $user_id_subscriber)
{
    $user_id_publisher = mysqli_real_escape_string($con, $user_id_publisher);
    $user_id_subscriber = mysqli_real_escape_string($con, $user_id_subscriber);
    $sql = "SELECT id FROM subscribe WHERE user_id_publisher = $user_id_publisher AND user_id_subscriber = $user_id_subscriber";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_subscribe ' . mysqli_error($con));
}


// Добавление новой записи в БД

/**
 * Добавление комментария
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id текущего пользователя
 * @param  string $comment - текст комментария
 * @return void
*/
function add_comment($con, $post_id, $current_user_id, $comment)
{
    $sql = "INSERT INTO comment (post_id, user_id, text) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $current_user_id, $comment]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_comment ' . mysqli_error($con));
    }
}

/**
 * Добавление просмотра поста
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @return void
*/
function add_views($con, $post_id)
{
    $sql = "UPDATE post SET views = views + 1 WHERE id = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_views ' . mysqli_error($con));
    }
}

/**
 * Добавление репоста поста
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id пользователя
 * @return void
*/
function add_repost($con, $post_id, $current_user_id)
{
    $post_id = mysqli_real_escape_string($con, $post_id);
    $sql = "SELECT id, user_id, type_id, title, text, quote, caption, photo_url,video_url, link_url, views
            FROM post WHERE id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $post = mysqli_fetch_assoc($result);


        mysqli_query($con, 'START TRANSACTION');
        $sql_1 = "UPDATE post SET repost_count = repost_count + 1 WHERE id = $post_id";
        $sql_2 = "INSERT INTO post SET
                    user_id = $current_user_id,
                    type_id = '{$post['type_id']}',
                    title = '{$post['title']}',
                    text = '{$post['text']}',
                    quote = '{$post['quote']}',
                    caption = '{$post['caption']}',
                    photo_url = '{$post['photo_url']}',
                    video_url = '{$post['video_url']}',
                    link_url = '{$post['link_url']}',
                    views = '{$post['views']}',
                    repost_post_id = {$post['id']}";

        $result_1 = mysqli_query($con, $sql_1);
        $result_2 = mysqli_query($con, $sql_2);

        if ($result_1 && $result_2) {
            mysqli_query($con, 'COMMIT');
        }
        else {
            mysqli_query($con, 'ROLLBACK');
        }
    }
}

/**
 * Добавление подписки
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id пользователя
 * @return void
*/
function add_subscribe($con, $user_id, $current_user_id)
{
    $sql = "INSERT INTO subscribe SET user_id_publisher = ?, user_id_subscriber = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $current_user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_subscribe ' . mysqli_error($con));
    }
}

/**
 * Добавление поста в избранное
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id пользователя
 * @return void
*/
function add_fav($con, $post_id, $current_user_id)
{
    $sql = "INSERT INTO fav (post_id, user_id) VALUES (?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $current_user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_fav' . mysqli_error($con));
    }
}

/**
 * Добавление сообщения
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_sender - id отправителя
 * @param  int $user_id_recipient - id получателя
 * @param  int $message - текст сообщения
 * @return void
*/
function add_message($con, $user_id_sender, $user_id_recipient, $message)
{
    $sql = "INSERT INTO message (user_id_sender, user_id_recipient, text, is_new) VALUES (?, ?, ?, 1)";
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id_sender, $user_id_recipient, $message]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_message' . mysqli_error($con));
    }
}


// Удаление записи из БД

/**
 * Удаление поста из избранного
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $post_id - id поста
 * @param  int $current_user_id - id пользователя
 * @return void
*/
function remove_fav($con, $post_id, $current_user_id)
{
    $sql = "DELETE FROM fav WHERE post_id = ? AND user_id = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $current_user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('remove_fav ' . mysqli_error($con));
    }
}

/**
 * Удаление подписки
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_publisher - id автора
 * @param  int $user_id_subscriber - id подписчика
 * @return void
*/
function remove_subcribe($con, $user_id_publisher, $user_id_subscriber)
{
    $sql = "DELETE FROM subscribe WHERE user_id_publisher = ? AND user_id_subscriber = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id_publisher, $user_id_subscriber]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('remove_subcribe ' . mysqli_error($con));
    }
}

/**
 * Обнуление поля что сообщение новое
 *
 * @param  mysqli $con - ресурс соединения
 * @param  int $user_id_sender - id отправителя
 * @param  int $user_id_recipient - id получателя
 * @return void
 */
function remove_new_messages($con, $user_id_sender, $user_id_recipient)
{
    $user_id_sender = mysqli_real_escape_string($con, $user_id_sender);
    $user_id_recipient = mysqli_real_escape_string($con, $user_id_recipient);

    $sql = "UPDATE message SET is_new = 0
                WHERE user_id_sender = $user_id_sender
                AND user_id_recipient = $user_id_recipient";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error('get_count_user_posts ' . mysqli_error($con));
    }
}
