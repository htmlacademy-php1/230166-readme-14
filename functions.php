<?php

/**
 * Функция принимает строку, id поста для ссылки, и количество символов.
 * Функция должна возвращать результат: оригинальный текст, если его длина меньше заданного числа символов.
 * В противном случае это должен быть урезанный текст с прибавленной к нему ссылкой.
 *
 * @param  string $string текст поста
 * @param  int $post_id - id поста
 * @param  int $limit - число символов
 * @return string урезанный текст с ссылкой
*/
function crop_text($string, $post_id, $limit = 300)
{
    if (mb_strlen($string, 'utf-8') < $limit) {
        return '<p>' . $string . '</p>';
    }

    $count = 0;
    $result = [];

    $arr = explode(' ', $string);

    foreach($arr as $item) {
        array_push($result, $item);

        $count += mb_strlen($item, 'utf-8') + 1;

        if ($count > ($limit + 1)) {
            $result = implode(' ', $result);
            return "<p>" . $result . "...</p>\n<a class='post-text__more-link' href='post.php?post_id=$post_id'>Читать далее</a>";
        }
    }
}

/**
 * Защита от XSS атак, заменяет специальные символы на безопасные
 *
 * @param  string - Конвертируемая строка
 * @return string - отконвертированная строка
 */
function esc($string)
{
    return htmlspecialchars($string);
}

/**
 * Функция принимает дату, переводит в Unix и возвращает сколько прошло времени
 * относительно от текущего времени в минутах, часах, неделях, месяцах
 *
 * @param  string
 * @return string
*/
function get_relative_date($date)
{
    $cur_date = time();
    $post_date = strtotime($date);
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
 * Функция переводит переданную дату в Unix и возвращает в виде дд.мм.гггг чч:мм
 *
 * @param  string
 * @return string
*/
function get_date_for_title($date)
{
    return date('d.m.Y H:i', strtotime($input));
}

/**
 * Функция принимает данные из формы и список обязательных полей. Сравнивает, если поле из формы
 * является обязательным и оно не заполненно, то поле добавляется в массив с ошибками
 *
 * @param  array $fields - все поля
 * @param  array $reqiured_fields - обязательные поля
 * @return array
*/
function get_required_errors($fields, $reqiured_fields)
{
    $errors = [];

    foreach ($fields as $field => $value) {
        if (array_key_exists($field, $reqiured_fields) && empty($value)) {
            $errors[$field] = "$reqiured_fields[$field]. Это поле должно быть заполнено.";
        }
    }

    return $errors;
}

/**
 * Функция проверяет количество символов в сообщении, максимальное и минимальное значение,
 * возвращает true или false
 *
 * @param  string $string - проверяемая строка
 * @param  int $min - минимальное количество символов в строке
 * @param  int $max - максимальное количество символов в строке
 * @return bool
 */
function check_length_of_string($string, $min, $max)
{
    if ($string) {
        $len = strlen($string);
        if ($len >= $min or $len <= $max) {
            return true;
        }
    }

    return false;
}

/**
 * Функция принимает ссылку, и возвращает сообщение об ошибке, если формат ссылки неправильный
 *
 * @param  string
 * @return string
 */
function validate_url($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return 'Неправильный формат ссылки';
    }

    return null;
}

/**
 * Функция принимает email, и возвращает ошибку, если формат email неправильный
 *
 * @param  string
 * @return string
 */
function validate_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Неправильный формат почты';
    }

    return null;
}

/**
 * Функция принимает массив, и удаляет пробелы в начале и конце
 * у каждого элемента массива
 *
 * @param  array
 * @return array
 */
function trim_array($array)
{
    $result = [];

    foreach($array as $key => $value) {
        $result[$key] = trim($value);
    }

    return $result;
}

/**
 * Функция принимает ссылку и удаляет из неё GET параметры
 * Возвращает название сайта
 *
 * @param  string
 * @return string
 */
function get_page_url($url)
{
    $url = explode('?', $url);
    $url = $url[0];

    return $url;
}

/**
 * Функция принимает массив с данными, например пользователей, и id
 * полученное из POST или GET параметров. Выделяет из массива все id в отдельных массив,
 * и возвращает true или false, если в этим массиве есть полученное id
 *
 * @param  array $allowed_list Список существующих данных
 * @param  int $id проверяемое id
 * @return bool
 */
function check_id($allowed_list, $id)
{
    $ids = array_column($allowed_list, 'id');

    if (!in_array($id, $ids)) {
        return true;
    }

    return false;
}

/**
 * Принимает массив или строку и символ. Если получена строка,
 * то она переводится в массив. Циклом проверяется есть ли в начале
 * каждого элемента переданный символ, например решётка для тэгов.
 * Если нет, то этот символ добавляется в начале элемента
 *
 * @param  array $array
 * @param  string $sign
 * @return array  массив, например с обрешеченными тэгами
 */
function insert_first_sign($array, $sign)
{
    $array = is_array($array) ? $array : explode(' ', $array);
    $result = [];

    foreach($array as $item) {
        if (mb_substr($item, 0, 1) === $sign) {
            $result[] = $item;
        } else {
            $result[] = $sign . $item;
        }
    }

    return $result;
}
