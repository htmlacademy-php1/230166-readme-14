-- types
INSERT INTO type (name, class, icon_width, icon_height)
  VALUES
  ('Текст', 'text', 20, 21),
  ('Цитата', 'quote', 21, 20),
  ('Картинка', 'photo',  22, 18),
  ('Видео', 'video', 24, 16),
  ('Ссылка', 'link', 21, 18);

-- users
INSERT INTO user
  SET email = 'elvira@mail.com',
      login = 'Эльвира Хайпулинова',
      password = '111111',
      avatar = 'img/userpic-elvira.jpg';

INSERT INTO user
  SET email = 'tanya@mail.com',
      login = 'Таня Фирсова',
      password = '222222',
      avatar = 'img/userpic-tanya.jpg';

INSERT INTO user
  SET email = 'petro@mail.com',
      login = 'Петр Демин',
      password = '333333',
      avatar = 'img/userpic-petro.jpg';

INSERT INTO user
  SET email = 'mark@mail.com',
      login = 'Марк Смолов',
      password = '444444',
      avatar = 'img/userpic-mark.jpg';

INSERT INTO user
  SET email = 'larisa@mail.com',
      login = 'Лариса Роговая',
      password = '555555',
      avatar = 'img/userpic-larisa.jpg';

-- posts
INSERT INTO post
  SET user_id = 1,
      type_id = 2,
      title = 'Цитата',
      quote = 'Тысячи людей живут без любви, но никто — без воды.',
      caption = 'Xью Оден',
      views = 3;

INSERT INTO post
  SET user_id = 1,
      type_id = 1,
      title = 'Полезный пост про Байкал',
      text = 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках. Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.',
      views = 5;

INSERT INTO post
  SET user_id = 2,
      type_id = 3,
      title = 'Наконец, обработала фотки!',
      photo_url = 'img/rock.jpg',
      views = 6;

INSERT INTO post
  SET user_id = 2,
      type_id = 4,
      title = 'Полезный пост про Байкал',
      video_url = 'https://youtu.be/DAP6oTtUcZ4',
      views = 8;

INSERT INTO post
  SET user_id = 3,
      type_id = 5,
      title = 'Делюсь с вами ссылочкой',
      link_url = 'https://vitadental.ru',
      views = 2;

INSERT INTO post
  SET user_id = 5,
      type_id = 2,
      title = 'Цитата',
      quote = 'Тысячи людей живут без любви, но никто — без воды.',
      caption = 'Xью Оден',
      views = 1;

-- comments
INSERT INTO comment
  SET user_id = 1,
      post_id = 1,
      text = 'Озеро Байкал – огромное древнее озеро в горах Сибири.';

INSERT INTO comment
  SET user_id = 1,
      post_id = 2,
      text = 'Озеро Байкал – огромное древнее озеро.';

INSERT INTO comment
  SET user_id = 2,
      post_id = 1,
      text = 'Байкал считается самым глубоким озером в мире.';

INSERT INTO comment
  SET user_id = 2,
      post_id = 2,
      text = 'Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой.';

INSERT INTO comment
  SET user_id = 3,
      post_id = 2,
      text = 'Красота!!!1!';

INSERT INTO comment
  SET user_id = 4,
      post_id = 3,
      text = 'Красота!!!3!';

-- Сообщения
INSERT INTO message
  SET user_id_sender = 1,
      user_id_recipient = 2,
      text = 'Красота!!!1!';

INSERT INTO message
  SET user_id_sender = 2,
      user_id_recipient = 1,
      text = 'Красота!!!2!';

INSERT INTO message
  SET user_id_sender = 3,
      user_id_recipient = 1,
      text = 'Красота!!!3!';

INSERT INTO message
  SET user_id_sender = 3,
      user_id_recipient = 2,
      text = 'Красота!!!4!';

INSERT INTO message
  SET user_id_sender = 3,
      user_id_recipient = 1,
      text = 'Красота!!!5!';

-- лайки
INSERT INTO fav (user_id, post_id) VALUES (1, 3), (2, 1), (3, 1), (4, 1), (5, 1), (2, 5), (2, 6);

-- подписки
INSERT INTO subscribe (user_id_subscriber, user_id_publisher) VALUES (1, 2), (2, 1), (3, 1), (4, 1), (1, 3), (1, 4);

-- хэштеги
INSERT INTO tag (text) VALUES ('#nature'), ('#globe'), ('#photooftheday'), ('#canon'), ('#landscape'), ('#щикарныйвид');

INSERT INTO post_tag (post_id, tag_id) VALUES (1, 1), (6, 1), (5, 1), (5, 2), (3, 1), (1, 2), (1, 3), (1, 3), (1, 4), (2, 3), (3, 3), (3, 4);

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT p.id, p.created_at, u.login, u.avatar, t.id type_id, t.name, t.class, p.title, p.text, p.caption, p.photo_url, p.video_url, p.link_url, p.views FROM post p
  JOIN user u ON p.user_id = u.id
  JOIN type t ON p.type_id = t.id
  ORDER BY p.views DESC LIMIT 6;

-- получить список постов для конкретного пользователя;
SELECT p.id, p.created_at, u.login, t.id type_id, t.name, p.title, p.text, p.caption, p.photo_url, p.video_url, p.link_url, p.views from post p
  JOIN user u ON p.user_id = u.id
  JOIN type t ON p.type_id = t.id
  WHERE u.id = 1;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT c.created_at, c.text, u.login FROM comment c
  JOIN post p ON c.post_id = p.id
  JOIN user u ON c.user_id = u.id
  WHERE p.id = 2;
