<?php
/**
 * Функция должна возвращать результат: оригинальный текст, если его длина меньше заданного числа символов.
 * В противном случае это должен быть урезанный текст с прибавленной к нему ссылкой.
 * @param string, int
 * @return string
*/
function crop_text(string $input, int $limit = 300):string
{
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
function esc($input):string
{
    $output = htmlspecialchars($input);
    // $output = strip_tags($str);

    return $output;
}

/**
 * Часовой пояс по умолчанию
*/
date_default_timezone_set("Europe/Moscow");

/**
 * Функция форматирует дату в относительном виде
 * @param string
 * @return string
*/
function get_relative_date(string $input):string
{
    $cur_date = time();
    $post_date = strtotime($input);
    $dif_date = $cur_date - $post_date;

    if($dif_date < 60) {
        return $dif_date . ' ' . get_noun_plural_form($dif_date, 'секунда', 'секунды', 'секунд') . ' назад';
    } elseif ($dif_date < 3600) {
        $minuts = floor($dif_date / 60);
        return $minuts . ' ' . get_noun_plural_form($minuts, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($dif_date < 86400) {
        $hours = floor($dif_date / 3600);
        return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
    } elseif ($dif_date < 604800) {
        $days = floor($dif_date / 86400);
        return $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней') . ' назад';
    } elseif ($dif_date < 3024000) {
        $weeks = floor($dif_date / 604800);
        return $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' назад';
    } else {
        $months = floor($dif_date / 3024000);
        return $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' назад';
    }
}

/**
 * Функция форматирует дату в виде дд.мм.гггг чч: мм
 * @param string
 * @return string
*/
function get_date_for_title(string $input):string
{
    return date('d.m.Y H:i', strtotime($input));
}
