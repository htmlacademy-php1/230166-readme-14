-- список типов контента для поста
INSERT INTO type (name, class)
  VALUES ('Текст', 'post-text'), ('Цитата', 'post-quote'), ('Картинка', 'post-photo'), ('Видео', 'post-video'), ('Ссылка', 'post-link');

-- придумайте пару пользователей
INSERT INTO user
  SET email = 'elvira@test.com',
      login = 'Эльвира Хайпулинова',
      password = '123qwe',
      avatar = 'userpic-elvira.jpg';

INSERT INTO user
  SET email = 'tanya@test.com',
      login = 'Таня Фирсова',
      password = '111111',
      avatar = 'userpic-tanya.jpg';

-- существующий список постов
INSERT INTO post
  SET user_id = 1,
      type_id = 1,
      title = 'Полезный пост про Байкал',
      text = 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.',
      views = 5;

INSERT INTO post
  SET user_id = 1,
      type_id = 2,
      title = 'Цитата',
      text = 'Тысячи людей живут без любви, но никто — без воды.',
      caption = 'Xью Оден',
      views = 3;

INSERT INTO post
  SET user_id = 2,
      type_id = 3,
      title = 'Наконец, обработала фотки!',
      img = 'coast-adding.png',
      views = 6;

INSERT INTO post
  SET user_id = 1,
      type_id = 3,
      title = 'Наконец, обработала фотки!',
      img = 'coast-adding.png',
      views = 8;

-- придумайте пару комментариев к разным постам;
INSERT INTO comment
  SET user_id = 1,
      post_id = 2,
      text = 'Озеро Байкал – огромное древнее озеро в горах Сибири.';

INSERT INTO comment
  SET user_id = 2,
      post_id = 1,
      text = 'Озеро Байкал – огромное древнее озеро.';

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT p.created_at, u.login, t.name, p.title, p.text, p.caption, p.img, p.video, p.link, p.views FROM post p
  JOIN user u ON p.user_id = u.id
  JOIN type t ON p.type_id = t.id
  ORDER BY p.views DESC;

-- получить список постов для конкретного пользователя;
SELECT p.created_at, u.login, t.name, p.title, p.text, p.caption, p.img, p.video, p.link, p.views from post p
  JOIN user u ON p.user_id = u.id
  JOIN type t ON p.type_id = t.id
  WHERE u.id = 1;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT c.created_at, c.text, u.login FROM comment c
  JOIN post p ON c.post_id = p.id
  JOIN user u ON c.user_id = u.id
  WHERE p.id = 2;

-- добавить лайк к посту;
INSERT INTO fav (user_id, post_id) VALUES (1, 1);

-- подписаться на пользователя.
INSERT INTO subscribe (user_id_subscriber, user_id_publisher) VALUES (2, 1);
