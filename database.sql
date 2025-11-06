-- ฐานข้อมูลสำหรับ Game E-commerce
CREATE DATABASE IF NOT EXISTS simple_shop;
USE simple_shop;

-- ตาราง products (เกม)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price VARCHAR(50) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    category ENUM('steam', 'epic', 'origin', 'uplay', 'gog', 'mobile') DEFAULT 'steam',
    genres VARCHAR(255),
    steam_id INT,
    rating DECIMAL(3,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง users (ผู้ใช้)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง orders (คำสั่งซื้อ)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    product_id INT,
    quantity INT DEFAULT 1,
    total VARCHAR(50),
    platform VARCHAR(50),
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: admin123

INSERT INTO products (name, price, description, image, category, genres, rating) VALUES 
('Cyberpunk 2077', '฿1,999', 'เกม RPG โลกอนาคตที่เต็มไปด้วยเทคโนโลยี', 'https://cdn.akamai.steamstatic.com/steam/apps/1091500/header.jpg', 'steam', 'RPG, Action', 4.2),
('Valorant', 'Free', 'เกมยิง FPS แบบ 5v5 จาก Riot Games', 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt5df9e4cda9393c2d/5eb26f1e2b2aba6e6c0e8e8a/V_AGENTS_587x900_Jett.jpg', 'epic', 'FPS, Tactical', 4.5),
('Minecraft', '฿890', 'เกมสร้างโลกและเอาชีวิตรอดที่ได้รับความนิยม', 'https://www.minecraft.net/content/dam/games/minecraft/key-art/Games_Subnav_Minecraft-300x465.jpg', 'steam', 'Sandbox, Survival', 4.8),
('Genshin Impact', 'Free', 'เกม Action RPG โลกเปิดสไตล์อนิเมะ', 'https://webstatic.hoyoverse.com/upload/event/2021/02/25/3e2c8e5d2b8c7b9c8f8e8e8e8e8e8e8e.jpg', 'mobile', 'RPG, Adventure', 4.3),
('Among Us', '฿159', 'เกมหาตัวผู้ทรยศในกลุ่ม', 'https://cdn.akamai.steamstatic.com/steam/apps/945360/header.jpg', 'steam', 'Party, Multiplayer', 4.1),
('Fall Guys', 'Free', 'เกมแข่งขันสุดฮาแบบ Battle Royale', 'https://cdn.akamai.steamstatic.com/steam/apps/1097150/header.jpg', 'epic', 'Party, Battle Royale', 4.0);
