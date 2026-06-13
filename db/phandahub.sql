-- ============================================================
-- PhandaHub Marketplace - Database Schema & Sample Data
-- Import this file via phpMyAdmin (XAMPP) to create the database.
-- ============================================================

CREATE DATABASE IF NOT EXISTS phandahub
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE phandahub;

-- ------------------------------------------------------------
-- Users table - stores registered customers/sellers
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  full_name    VARCHAR(100) NOT NULL,
  email        VARCHAR(150) NOT NULL UNIQUE,
  phone        VARCHAR(20)  NOT NULL,
  password     VARCHAR(255) NOT NULL,        -- hashed with password_hash()
  wallet       DECIMAL(10,2) NOT NULL DEFAULT 500.00,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Admins table - separate from users for security
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Products table - listings created by users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  title        VARCHAR(150) NOT NULL,
  description  TEXT NOT NULL,
  price        DECIMAL(10,2) NOT NULL,
  category     VARCHAR(50)  NOT NULL,
  image        VARCHAR(255) NOT NULL,
  stock        INT NOT NULL DEFAULT 1,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Cart table - one row per user/product pair
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cart (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  product_id INT NOT NULL,
  quantity   INT NOT NULL DEFAULT 1,
  added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Orders table - one row per checkout
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  total       DECIMAL(10,2) NOT NULL,
  status      VARCHAR(30) NOT NULL DEFAULT 'Paid',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Order items table - line items per order
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT NOT NULL,
  product_id INT NOT NULL,
  title      VARCHAR(150) NOT NULL,
  price      DECIMAL(10,2) NOT NULL,
  quantity   INT NOT NULL,
  FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SAMPLE DATA
-- Default admin login -> username: admin   password: admin123
-- Default user  login -> email: thabo@demo.com  password: password123
-- ============================================================

-- Admin (password hash for "admin123")
INSERT INTO admins (username, password) VALUES
('admin', '$2b$10$18OADlYkpLpABN6IxOloK.pBxsFjJPzQxcanpFPLatciSyK4FuTk2');

-- Demo users (password hash for "password123")
INSERT INTO users (full_name, email, phone, password, wallet) VALUES
('Thabo Mokoena',  'thabo@demo.com',  '0712345678', '$2b$10$A91gJQokZ4IcOLpj2ZP6lOHe9o36U1RzBvf22Waq9m1pn24DkuaqC', 1500.00),
('Lerato Dlamini', 'lerato@demo.com', '0823456789', '$2b$10$A91gJQokZ4IcOLpj2ZP6lOHe9o36U1RzBvf22Waq9m1pn24DkuaqC', 1000.00);

-- Sample products (image column points to remote placeholders; you can
-- replace these with files inside /images/products/ at any time)
INSERT INTO products (user_id, title, description, price, category, image, stock) VALUES
(1, 'Vintage Leather Jacket',  'Genuine leather jacket, lightly worn, size M.',           450.00, 'Fashion',     'https://picsum.photos/seed/jacket/600/400', 2),
(1, 'Acoustic Guitar',         'Yamaha F310 acoustic guitar with soft case.',            1800.00, 'Music',       'https://picsum.photos/seed/guitar/600/400', 1),
(2, 'Samsung Galaxy A14',      'Used phone in great condition, 64GB, comes with charger.',2300.00, 'Electronics', 'https://picsum.photos/seed/phone/600/400',  3),
(2, 'Mountain Bike',           '21-speed mountain bike, recently serviced.',             2750.00, 'Sports',      'https://picsum.photos/seed/bike/600/400',   1),
(1, 'Coffee Table',            'Solid wood coffee table, 120x60cm.',                      650.00, 'Furniture',   'https://picsum.photos/seed/table/600/400',  1),
(2, 'PlayStation 4 Controller','Original DualShock 4, black.',                            550.00, 'Gaming',      'https://picsum.photos/seed/ps4/600/400',    4);
