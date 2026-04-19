CREATE DATABASE IF NOT EXISTS books_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE books_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password) VALUES
  ('admin', '1234'),
  ('user', 'abcd')
ON DUPLICATE KEY UPDATE username = VALUES(username);
