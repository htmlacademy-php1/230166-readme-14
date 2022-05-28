DROP DATABASE IF EXISTS readme;

CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE readme;

CREATE TABLE user (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL,
  login VARCHAR(255) NOT NULL,
  password CHAR(255) NOT NULL,
  avatar VARCHAR(255) NULL DEFAULT NULL,

  UNIQUE INDEX user_email (email),
  UNIQUE INDEX user_login (login)
);

CREATE TABLE type (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  class VARCHAR(255) NULL DEFAULT NULL,
  icon_width TINYINT,
  icon_height TINYINT
);

CREATE TABLE post (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT(11),
  type_id INT(11),
  title TEXT NOT NULL,
  text TEXT NULL DEFAULT NULL,
  quote TEXT NULL DEFAULT NULL,
  caption TEXT NULL DEFAULT NULL,
  photo_url VARCHAR(500) NULL DEFAULT NULL,
  video_url VARCHAR(500) NULL DEFAULT NULL,
  link_url VARCHAR(500) NULL DEFAULT NULL,
  views INT UNSIGNED NULL DEFAULT 0,
  repost_count INT UNSIGNED NULL DEFAULT 0,
  repost_post_id INT(11) NULL DEFAULT NULL,

  FULLTEXT INDEX post_text (title, text),

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (type_id) REFERENCES type(id)
);

CREATE TABLE comment (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  post_id INT(11),
  user_id INT(11),
  text TEXT NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE fav (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  post_id INT(11),
  user_id INT(11),

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE subscribe (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id_publisher INT(11),
  user_id_subscriber INT(11),

  FOREIGN KEY (user_id_subscriber) REFERENCES user(id),
  FOREIGN KEY (user_id_publisher) REFERENCES user(id)
);

CREATE TABLE message (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id_sender INT(11),
  user_id_recipient INT(11),
  is_new TINYINT(1) DEFAULT 0,
  text TEXT NOT NULL,

  FOREIGN KEY (user_id_sender) REFERENCES user(id),
  FOREIGN KEY (user_id_recipient) REFERENCES user(id)
);

CREATE TABLE tag (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  text TINYTEXT NOT NULL
);

CREATE TABLE post_tag (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  post_id INT(11),
  tag_id INT(11),

  FOREIGN KEY (post_id) REFERENCES post(id),
  FOREIGN KEY (tag_id) REFERENCES tag(id)
);
