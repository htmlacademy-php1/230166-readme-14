<?php
function get_popular_posts() {
    return [
        0 => [
            'title' => 'Цитата',
            'type' => 'post-quote',
            'quote' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
            'user_name' => 'Лариса',
            'avatar' => 'userpic-larisa-small.jpg'
        ],
        1 => [
            'title' => 'Игра престолов',
            'type' => 'post-text',
            'text' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
            'user_name' => 'Владик',
            'avatar' => 'userpic.jpg'
        ],
        2 => [
            'title' => 'Наконец, обработал фотки!',
            'type' => 'post-photo',
            'photo' => 'rock-medium.jpg',
            'user_name' => 'Виктор',
            'avatar' => 'userpic-mark.jpg'
        ],
        3 => [
            'title' => 'Моя мечта',
            'type' => 'post-photo',
            'photo' => 'coast-medium.jpg',
            'user_name' => 'Лариса',
            'avatar' => 'userpic-larisa-small.jpg'
        ],
        4 => [
            'title' => 'Лучшие курсы',
            'type' => 'post-link',
            'subtitle' => 'Курсы для всех'
            'link' => 'www.htmlacademy.ru',
            'user_name' => 'Владик',
            'avatar' => 'userpic.jpg'
        ]
    ];
}
