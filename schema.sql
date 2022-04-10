DROP DATABASE readme;

CREATE DATABASE IF NOT EXISTS readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(128) NOT NULL UNIQUE,
  login VARCHAR(255) NOT NULL UNIQUE,
  password CHAR(64) NOT NULL,
  avatar VARCHAR(255) DEFAULT NULL,

  UNIQUE INDEX email_index (email),
  UNIQUE INDEX login_index (login)
);

CREATE TABLE types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  class TINYTEXT DEFAULT NULL
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  author INT,
  type INT,
  title VARCHAR(255) NOT NULL,
  text TEXT DEFAULT NULL,
  caption VARCHAR(255) DEFAULT NULL,
  img VARCHAR(255) DEFAULT NULL,
  video VARCHAR(255) DEFAULT NULL,
  link VARCHAR(255) DEFAULT NULL,
  views INT UNSIGNED DEFAULT 0,

  INDEX author_index (author),
  INDEX type_index (type),
  FULLTEXT INDEX title_index (title),
  FULLTEXT INDEX text_index (text),

  FOREIGN KEY (author) REFERENCES users(id),
  FOREIGN KEY (type) REFERENCES types(id)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  author INT,
  post INT,
  content MEDIUMTEXT NOT NULL,

  INDEX author_index (author),

  FOREIGN KEY (author) REFERENCES users(id),
  FOREIGN KEY (post) REFERENCES posts(id)
);

CREATE TABLE likes (
  author INT,
  post INT,

  INDEX author_index (author),

  FOREIGN KEY (author) REFERENCES users(id),
  FOREIGN KEY (post) REFERENCES posts(id)
);

CREATE TABLE subscribes (
  subscriber INT,
  publisher INT,

  FOREIGN KEY (subscriber) REFERENCES users(id),
  FOREIGN KEY (publisher) REFERENCES users(id)
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sender INT,
  recipient INT,
  content MEDIUMTEXT NOT NULL,

  INDEX sender_index (sender),
  INDEX recipient_index (recipient),

  FOREIGN KEY (sender) REFERENCES users(id),
  FOREIGN KEY (recipient) REFERENCES users(id)
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag VARCHAR(255)
);

-- связь вида «многие-ко-многим»
CREATE TABLE post_hashtag (
  post INT,
  hashtag INT,

  FOREIGN KEY (post) REFERENCES posts(id),
  FOREIGN KEY (hashtag) REFERENCES hashtags(id)
);
