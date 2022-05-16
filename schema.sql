DROP DATABASE IF EXISTS readme;

CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE readme;

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL,
  login VARCHAR(255) NOT NULL,
  password CHAR(255) NOT NULL,
  avatar VARCHAR(255) DEFAULT NULL,

  UNIQUE INDEX user_email (email),
  UNIQUE INDEX user_login (login)
);

CREATE TABLE type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  class VARCHAR(255) DEFAULT NULL,
  icon_width TINYINT,
  icon_height TINYINT
);

CREATE TABLE post (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  type_id INT,
  title TEXT NOT NULL,
  text TEXT DEFAULT NULL,
  quote TEXT DEFAULT NULL,
  caption TEXT DEFAULT NULL,
  photo_url VARCHAR(255) DEFAULT NULL,
  video_url VARCHAR(255) DEFAULT NULL,
  link_url VARCHAR(255) DEFAULT NULL,
  views INT UNSIGNED DEFAULT 0,

  FULLTEXT INDEX post_text (title, text),

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (type_id) REFERENCES type(id)
);

CREATE TABLE comment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  post_id INT,
  user_id INT,
  text TEXT NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE fav (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  user_id INT,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
);

CREATE TABLE subscribe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id_publisher INT,
  user_id_subscriber INT,

  FOREIGN KEY (user_id_subscriber) REFERENCES user(id),
  FOREIGN KEY (user_id_publisher) REFERENCES user(id)
);

CREATE TABLE message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id_sender INT,
  user_id_recipient INT,
  content TEXT NOT NULL,

  FOREIGN KEY (user_id_sender) REFERENCES user(id),
  FOREIGN KEY (user_id_recipient) REFERENCES user(id)
);

CREATE TABLE tag (
  id INT AUTO_INCREMENT PRIMARY KEY,
  text TINYTEXT NOT NULL
);

CREATE TABLE post_tag (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  tag_id INT,

  FOREIGN KEY (post_id) REFERENCES post(id),
  FOREIGN KEY (tag_id) REFERENCES tag(id)
);
