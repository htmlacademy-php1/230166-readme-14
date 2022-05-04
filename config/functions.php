<?php
/**
 * Функция должна возвращать результат: оригинальный текст, если его длина меньше заданного числа символов.
 * В противном случае это должен быть урезанный текст с прибавленной к нему ссылкой.
 * @param string $input
 * @param int $post_id
 * @param int $limit
 * @return string
*/
function crop_text(string $input, $post_id, int $limit = 300):string {
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
            return "<p>" . $output . "...</p>\n<a class='post-text__more-link' href='post.php?post_id=$post_id'>Читать далее</a>";
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
 * Получение ошибок для незаполненных обязательных полей
 * @param array $post
 * @param array $reqiured
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
 * Возвращает ошибку если url передан в неправильном формате
 * @param string
 * @return mixed
 */
function validate_url($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return 'Неправильный формат ссылки';
    }

    return NULL;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 * @return string Ошибку если валидация не прошла
 */
function validate_youtube_url($url) {

    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return NULL;
}
