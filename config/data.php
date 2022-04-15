<?php
define('POST_LIMIT', 300);

$user = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'database' => 'readme'
];

$is_auth = rand(0, 1);
$user_name = 'Margarita';
$popular_posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'date' => generate_random_date(0)
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'date' => generate_random_date(1)
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'user_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg',
        'date' => generate_random_date(2)
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'date' => generate_random_date(3)
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'date' => generate_random_date(4)
    ],
    [
        'title' => 'Байкал',
        'type' => 'post-text',
        'content' => 'Байкал – самое глубоководное озеро в мире, оно расположено в Восточной Сибири. Также Байкал - самое большое пресноводное озеро в Евразии. В нем содержится 20% всей пресной воды на Земле. Уникальная особенность озера - его необычайно чистая и прозрачная вода, богатство животного и растительного мира. Байкал – одно из главных природных достояний России, источник множества легенд и загадок. Он считается «местом силы» и особой энергетики.',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'date' => generate_random_date(5)
    ]
];
