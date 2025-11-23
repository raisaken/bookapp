-- Create database (change name as needed)
CREATE DATABASE IF NOT EXISTS book_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE book_app;

-- users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- books table
CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  genre VARCHAR(100) NOT NULL,
  year INT NULL,
  description TEXT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample user (replace the password hash locally if needed)
-- Password used in example: password123 (but hash below may differ by system)
INSERT INTO users (username, email, password) VALUES
('student', 'student@example.com', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8Uq8H6nG/e2f6v5Qz1Y1o6qz0wDq6');

-- sample books
INSERT INTO books (title, author, genre, year, description, created_by) VALUES
('Dune', 'Frank Herbert', 'Sci-Fi', 1965, 'Epic sci-fi novel.', 1),
('Neuromancer', 'William Gibson', 'Sci-Fi', 1984, 'Cyberpunk classic.', 1),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 1937, 'Prequel to LOTR.', 1);
