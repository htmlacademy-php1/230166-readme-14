<?php
/**
 * Функция должна возвращать результат: оригинальный текст, если его длина меньше заданного числа символов.
 * В противном случае это должен быть урезанный текст с прибавленной к нему ссылкой.
 * @param string, int
 * @return string
*/
function crop_text(string $input, int $limit = 300):string {
    if (mb_strlen($input, 'utf-8') < $limit) {
        return '<p>' . $input . '</p>';
    }

    $count = 0;
    $output = [];

    $arr = explode(' ', $input);

    foreach($arr as $item) {
        array_push($output, $item);

        $count += mb_strlen($item, 'utf-8') + 1;

        if ($count > ($limit + 1)) {
            $output = implode(' ', $output);
            return "<p>" . $output . "...</p>\n<a class='post-text__more-link' href='#'>Читать далее</a>";
        }
    }
}

/**
 * Функция фильтрует текст полученный от пользователя для защиты от XSS
 * @param string
 * @return string
*/
function esc($input):string {
    return htmlspecialchars($input);
}

/**
 * Получение относительной даты
 * @param string
 * @return string
*/
function get_relative_date(string $input):string {
    $cur_date = time();
    $post_date = strtotime($input);
    $dif_date = $cur_date - $post_date;

    if ($dif_date < 3600) {
        $minuts = floor($dif_date / 60);
        return $minuts . ' ' . get_noun_plural_form($minuts, 'минута', 'минуты', 'минут') . ' назад';
    }

    if ($dif_date < 86400) {
        $hours = floor($dif_date / 3600);
        return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
    }

    if ($dif_date < 604800) {
        $days = floor($dif_date / 86400);
        return $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней') . ' назад';
    }

    if ($dif_date < 3024000) {
        $weeks = floor($dif_date / 604800);
        return $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' назад';
    }

    $months = floor($dif_date / 3024000);
    return $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' назад';
}

/**
 * Получение даты в виде дд.мм.гггг чч: мм
 * @param string
 * @return string
*/
function get_date_for_title(string $input):string {
    return date('d.m.Y H:i', strtotime($input));
}

/**
 * Вывод ошибки в отдельном шаблоне error.php
 * @param string
 * @return string
*/
function show_error($error) {
    $page_content = include_template('error.php', ['error' => $error]);
    exit($page_content);
}

/**
 * типы категорий
 * @param $con mysqli Ресурс соединения
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
 * типы категорий
 * @param $con mysqli Ресурс соединения
 * @return array or string
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
 * @param $con mysqli Ресурс соединения
 * @param $type_id mysqli Ресурс соединения
 * @return array or string
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
 * @param $con mysqli Ресурс соединения
 * @param $post_id int
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
 * @param $con mysqli Ресурс соединения
 * @param $post_id int
 * @return array
*/
function get_comments($con, $post_id): array {
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
 * @param $con mysqli Ресурс соединения
 * @return array
*/
function get_tags($con, $post_id): array {
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
 * @param $con mysqli Ресурс соединения
 * @return int
*/
function get_count_favs($con, $post_id): int {
    $sql = "SELECT COUNT(id) AS count FROM fav WHERE post_id = $post_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }

    show_error(mysqli_error($con));
}

/**
 * Количество коментариев
 * @param $con mysqli Ресурс соединения
 * @param $post_id int
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
 * @param $con mysqli Ресурс соединения
 * @param $user_id int
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
 * @param $con mysqli Ресурс соединения
 * @param $user_id int
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
 * Получение данных из массива GET
 * @param $name string
 * @return any
*/
function get_parametr($name) {
    return filter_input(INPUT_GET, $name);
}

/**
 * Получение данных из массива POST
 * @param $name string
 * @return any
*/
function post_parametr($name) {
    return filter_input(INPUT_POST, $name);
}

/**
 * Получение ошибок для незаполненных обязательных полей
 * @param $post array
 * @param $reqiured array
 * @return array
*/
function get_required_errors($array, $required) {
    $errors = [];

    foreach ($array as $key => $value) {
        if (array_key_exists($key, $required) && empty($value)) {
            $errors[$key] = "$required[$key]. Это поле должно быть заполнено.";
        }
    }

    return $errors;
}

/**
 * Валидирует поле категории, если такой категории нет в списке
 * возвращает сообщение об этом
 * @param int $id категория, которую ввел пользователь в форму
 * @param array $allowed_list Список существующих категорий
 * @return string Текст сообщения об ошибке
 */
function validate_type ($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
}

/**
 * Валидирует количество символов в сообщении, максимальное и минимальное значение,
 * возвращает сообщение об ошибке
 * @param int $id категория, которую ввел пользователь в форму
 * @param array $allowed_list Список существующих категорий
 * @return string Текст сообщения об ошибке
 */
function validate_length($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение Должно быть от $min до $max символов";
        }
    }

    return null;
}

/**
 * Возвращает массив из объекта результата запроса
 * @param object $result_query mysqli Результат запроса к базе данных
 * @return array
 */
function fetch_result($result) {
    $row = mysqli_num_rows($result);

    if ($row === 1) {
        return mysqli_fetch_assoc($result);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает ошибку если передана ссылка в неправильном формате
 * @param string
 * @return array
 */
function validate_url($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return 'Неправильный формат ссылки';
    }

    return NULL;
}
