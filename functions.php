<?php
function crop_text(string $input, int $limit = 300) {
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

