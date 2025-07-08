CREATE DATABASE IF NOT EXISTS news_db;
USE news_db;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, 
  role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS articles (
  article_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image_url VARCHAR(255),
  published_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  category_id INT,
  author_id INT,
  FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
  FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$examplehashedpassword', 'admin'),
('aya', 'aya@example.com', '$2y$10$examplehashedpassword', 'user');

INSERT INTO categories (category_name) VALUES
('Politics'), 
('Sports'), 
('Technology'), 
('Health'), 
('Entertainment');

INSERT INTO articles (title, content, image_url, category_id, author_id) VALUES
('Elections 2025 Announced', 'Details...', 'http://localhost/News_website/assets/images/people.jpg', 1, 1),
('Team Wins...', 'Exciting...', 'http://localhost/News_website/assets/images/sports.jpg', 2, 2),
('AI Revolution 2025', 'How AI is changing the world.', 'http://localhost/News_website/assets/images/technology.jpg', 3, 2),
('Health Guide for Summer', 'Tips for staying healthy.', 'http://localhost/News_website/assets/images/health.jpg', 4, 1),
('Top 5 Movies This Year', 'Cinema hits reviewed.', 'http://localhost/News_website/assets/images/entertainment.jpg', 5, 2);

