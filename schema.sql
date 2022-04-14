CREATE DATABASE IF NOT EXISTS readme
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE readme;

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(128) NOT NULL,
  login VARCHAR(255) NOT NULL,
  password CHAR(6) NOT NULL,
  avatar VARCHAR(255) DEFAULT NULL,

  UNIQUE INDEX user_email (email),
  UNIQUE INDEX user_login (login)
);

CREATE TABLE type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  class VARCHAR(255) DEFAULT NULL
);

CREATE TABLE post (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  type_id INT,
  title VARCHAR(255) NOT NULL,
  text TEXT DEFAULT NULL,
  caption VARCHAR(255) DEFAULT 'Неизвестный Автор',
  img VARCHAR(255) DEFAULT NULL,
  video VARCHAR(255) DEFAULT NULL,
  link VARCHAR(255) DEFAULT NULL,
  views INT UNSIGNED DEFAULT 0,

  FULLTEXT INDEX post_text (title, text),

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (type_id) REFERENCES type(id)
);

CREATE TABLE comment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  post_id INT,
  text MEDIUMTEXT NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE fav (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  post_id INT,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE subscribe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id_subscriber INT,
  user_id_publisher INT,

  FOREIGN KEY (user_id_subscriber) REFERENCES user(id),
  FOREIGN KEY (user_id_publisher) REFERENCES user(id)
);

CREATE TABLE message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id_sender INT,
  user_id_recipient INT,
  content MEDIUMTEXT NOT NULL,

  FOREIGN KEY (user_id_sender) REFERENCES user(id),
  FOREIGN KEY (user_id_recipient) REFERENCES user(id)
);

CREATE TABLE hashtag (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag VARCHAR(255)
);

CREATE TABLE post_hashtag (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  hashtag_id INT,

  FOREIGN KEY (post_id) REFERENCES post(id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtag(id)
);
