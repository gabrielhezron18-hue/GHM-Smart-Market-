CREATE DATABASE IF NOT EXISTS smartmarket_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE smartmarket_db;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product VARCHAR(100) NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  location VARCHAR(180) NOT NULL,
  payment_method VARCHAR(80) NOT NULL,
  payment_reference VARCHAR(120) NULL,
  payment_status VARCHAR(40) NOT NULL DEFAULT 'Unverified',
  status VARCHAR(40) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  price VARCHAR(80) NOT NULL,
  image VARCHAR(500) NOT NULL,
  alt_text VARCHAR(180) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(40) NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(40) NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, price, image, alt_text)
SELECT 'Ringlight kwa video na picha', 'Ringlights zenye mwanga mkari kwa quality ya videos na pikchazi zako.', 'TSh 26,000+ to 79,000+', 'posta.zangu/lights.jpg', 'Ringlights original'
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Ringlight kwa video na picha');

INSERT INTO products (name, description, price, image, alt_text)
SELECT 'Microphone', 'Microphone kwa studio, mikutano, matangazo, na content creation.', 'TSh 60,000+', 'posta.zangu/microphone s.jpg', 'modern Microphone for quality sound recording'
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Microphone');

INSERT INTO products (name, description, price, image, alt_text)
SELECT 'Stand', 'Stand imara kwa microphone, simu, kamera, na vifaa vya ofisini.', 'TSh 35,000+', 'posta.zangu/stand.jpg', 'light, maiki na stand'
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Stand');

INSERT INTO products (name, description, price, image, alt_text)
SELECT 'posta', 'tangaza nasi matangazo ya biashara kwa njia ya posta, karibu sana GHM SmartMarket.', 'Tsh 15,000+', 'posta.zangu/Gii.jpg', 'Graphics designing'
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'posta');

INSERT INTO products (name, description, price, image, alt_text)
SELECT 'musical instruments', 'quality sound and music production with modern and high quality instruments.', 'bei ni nafuu sana', 'posta.zangu/instrumens.jpg', 'musical instruments'
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'musical instruments');

