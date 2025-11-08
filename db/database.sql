-- Database schema for PHP Game Store
-- ปรับปรุงโครงสร้างฐานข้อมูลให้สอดคล้องกับหน้าเว็บเกม

SET NAMES utf8mb4;

USE WebGameStore;

CREATE DATABASE IF NOT EXISTS php_website CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE php_website;

-- รีเซ็ตตารางเพื่ออัปเดตโครงสร้าง (สำหรับการพัฒนา/ทดสอบ)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ตาราง users สำหรับเก็บข้อมูลผู้ใช้
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    avatar_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตาราง categories สำหรับเกม/หมวดหมู่สินค้าบนหน้าเว็บ
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    banner_image VARCHAR(255),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตาราง products สำหรับสินค้าเกมและบัตรเติมเงิน
CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    product_type ENUM('PHYSICAL', 'DIGITAL_KEY', 'TOP_UP') NOT NULL DEFAULT 'PHYSICAL',
    requires_player_id TINYINT(1) NOT NULL DEFAULT 0,
    platform VARCHAR(120),
    image_url VARCHAR(255),
    stock_quantity INT NOT NULL DEFAULT 0,
    release_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตาราง posts สำหรับบทความ/ข่าวเกมบนหน้าเว็บ
CREATE TABLE IF NOT EXISTS posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    cover_image VARCHAR(255),
    author_id INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_author FOREIGN KEY (author_id)
        REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ข้อมูลเริ่มต้นสำหรับผู้ใช้ (รหัสผ่าน: admin123 / writer123)
INSERT INTO users (username, email, password, full_name, role, avatar_url) VALUES
('admin', 'admin@example.com', '$2y$12$l/q/goVeApoLKRYp.pBj9OEKZoEcemKpQwEamP.SYdoh4JNG2yLtC', 'Game Store Administrator', 'admin', NULL),
('writer', 'writer@example.com', '$2y$12$mtdXs2PL/uMCuCIZ7cKlyu2USrDrxelxUa9VkKoaPyysmvG9WIw9a', 'Content Team', 'staff', NULL);

-- หมวดหมู่/เกมหลักที่แสดงบนหน้าเว็บไซต์
INSERT INTO categories (name, description, banner_image, display_order) VALUES
('Resident Evil: Requiem', 'ภาคล่าสุดของแฟรนไชส์สยองขวัญที่กลับมาพร้อมโหมด Co-op และเนื้อเรื่องใหม่ทั้งหมด', 'https://img.asmedia.epimg.net/resizer/v2/ZKHOGS4GGNHPNJDWW4TCOXTKFY.jpg?width=1288&height=725', 1),
('Battlefield REDSEC', 'สงครามไซเบอร์ยุคอนาคตที่เน้นการสู้รบแบบทีม ปลดล็อกคลาสและสกินใหม่ๆ ได้ต่อเนื่อง', 'https://bsmedia.business-standard.com/_media/bs/img/article/2025-10/28/full/1761638363-2733.png', 2),
('EA SPORTS FC 26', 'สุดยอดเกมฟุตบอลแห่งปี รวมโหมด Ultimate Team และ Career ไว้ครบ', 'https://retrogems.fr/wp-content/uploads/2025/09/ea-sports-fc-26-ultimate-edition.jpg', 3),
('Duet Night Abyss', 'แอ็กชัน RPG มืดมนที่ผสานการต่อสู้สองอาชีพในหนึ่งตัวละคร พร้อมระบบ co-op เต็มรูปแบบ', 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3950020/ee582fcde01feabf17ed4e31a54a2b9f7e7284a3/header.jpg', 4),
('Global Top-Up & Gift Cards', 'บัตรเติมเงินและของขวัญดิจิทัลสำหรับแพลตฟอร์มต่างๆ ครอบคลุมทั้งคอนโซลและมือถือ', 'https://images.unsplash.com/photo-1587614382346-4ec892f9aca3?auto=format&fit=crop&w=1280&q=80', 5);

-- สินค้าตัวอย่างให้ตรงกับธีมเกมบนเว็บไซต์
INSERT INTO products (category_id, name, description, price, product_type, requires_player_id, platform, image_url, stock_quantity, release_date) VALUES
(1, 'Resident Evil: Requiem Deluxe Edition', 'เวอร์ชันดิจิทัลพิเศษมาพร้อม DLC Shadow Pursuit และสกินคลาสสิกสำหรับตัวละครหลัก', 1890.00, 'DIGITAL_KEY', 0, 'PC (Steam)', 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1024&q=80', 45, '2025-02-14'),
(1, 'Resident Evil: Requiem Collector\'s Pack', 'เซ็ตสะสมพร้อมกล่องเหล็ก ฟิกเกอร์ Leon และโค้ดชุดแต่งกายพิเศษสำหรับโหมด Mercenaries', 3590.00, 'PHYSICAL', 0, 'PlayStation 5', 'https://images.unsplash.com/photo-1605901309584-818e25960a8f?auto=format&fit=crop&w=1024&q=80', 18, '2025-02-14'),
(2, 'Battlefield REDSEC Ultimate Access', 'ปลดล็อกเนื้อเรื่องภาคเสริม Year One, Battle Pass 4 ซีซัน และโบนัส XP ถาวรสำหรับทีม REDSEC', 2390.00, 'DIGITAL_KEY', 0, 'PC (EA App)', 'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=1024&q=80', 60, '2025-03-01'),
(2, 'Battlefield REDSEC Arsenal Pack', 'แพ็คอาวุธและสกินหายากพร้อมชิปฮาร์ดแวร์สำหรับปรับแต่ง Drone และ Exo-Suit ในทุกโหมด', 1290.00, 'DIGITAL_KEY', 0, 'PC / Console Cross-Buy', 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1024&q=80', 80, '2025-03-01'),
(3, 'EA SPORTS FC 26 Ultimate Edition', 'ฉบับรวมครบพร้อมสิทธิ์เล่นล่วงหน้า 7 วัน โบนัส Ultimate Team และนักเตะตำนานแบบยืม', 2190.00, 'DIGITAL_KEY', 0, 'PlayStation / Xbox Cross-Gen', 'https://images.unsplash.com/photo-1526401485004-46910ecc8e51?auto=format&fit=crop&w=1024&q=80', 50, '2024-09-27'),
(3, 'EA SPORTS FC 26 Ultimate Team Points (5900)', 'เติมแต้ม 5,900 สำหรับสร้างทีมในโหมด Ultimate Team รองรับบัญชีโซนไทย', 1799.00, 'TOP_UP', 1, 'Ultimate Team Wallet', 'https://images.unsplash.com/photo-1605902711622-cfb43c44367f?auto=format&fit=crop&w=1024&q=80', 200, '2024-09-27'),
(4, 'Duet Night Abyss Founder\'s Bundle', 'แพ็คเริ่มต้นรวมชุดแฟชั่น Moonlit Oath, อาวุธระดับ S และ Battle Anthem soundtrack', 990.00, 'DIGITAL_KEY', 0, 'PC (Steam)', 'https://images.unsplash.com/photo-1626382701316-607f839aa1b1?auto=format&fit=crop&w=1024&q=80', 120, '2024-11-08'),
(4, 'Duet Night Abyss Crystal Bloom 6480', 'เติม Crystal Bloom 6,480 หน่วยพร้อมโบนัส VIP เพิ่มเติม เหมาะสำหรับผู้เล่นที่ต้องการเร่งเลเวล', 1490.00, 'TOP_UP', 1, 'Global Server', 'https://images.unsplash.com/photo-1518544889280-39d4f58c0e91?auto=format&fit=crop&w=1024&q=80', 150, '2024-11-08'),
(5, 'Nintendo eShop Gift Card 1000 THB', 'บัตรเติมเงิน Nintendo eShop โค้ดดิจิทัลพร้อมใช้งานภายใน 1 นาที รองรับบัญชีไทย', 990.00, 'DIGITAL_KEY', 0, 'Nintendo Switch', 'https://images.unsplash.com/photo-1618005198919-d3d4b5a92eee?auto=format&fit=crop&w=1024&q=80', 75, '2024-01-01'),
(5, 'PlayStation Network Wallet Top-Up 2300 THB', 'เติมเงินกระเป๋า PS Store โซนไทย ใช้ซื้อเกม ดิจิทัลดีล หรือสมัครสมาชิก PS Plus', 2100.00, 'DIGITAL_KEY', 0, 'PlayStation Network', 'https://images.unsplash.com/photo-1606813902919-d544dd54c60a?auto=format&fit=crop&w=1024&q=80', 65, '2024-01-01');

-- ข่าวและบทความสำหรับส่วนดีล/อัปเดตบนหน้าแรก
INSERT INTO posts (title, content, excerpt, status, cover_image, author_id) VALUES
('Resident Evil: Requiem เปิดให้พรีโหลดแล้ว พร้อมภารกิจ Co-op ใหม่', 'ทีมพัฒนา Capcom ยืนยันว่าเวอร์ชัน Deluxe สามารถพรีโหลดได้ก่อนวางจำหน่าย 72 ชั่วโมง พร้อมภารกิจพิเศษ Shadow Pursuit ที่รองรับผู้เล่น 4 คนแบบ cross-play เต็มรูปแบบ', 'เตรียมตัวลุยกับ Shadow Pursuit ภารกิจ Co-op สุดโหดก่อนใคร', 'published', 'https://img.asmedia.epimg.net/resizer/v2/ZKHOGS4GGNHPNJDWW4TCOXTKFY.jpg?width=1288&height=725', 2),
('Battlefield REDSEC อัปเดตซีซัน Zero-Day เพิ่มโหมดฮีโร่สุดมัน', 'ซีซัน Zero-Day เพิ่มฮีโร่ใหม่สองตัวพร้อมระบบสลับคลาสกลางเกม และปรับสมดุลอาวุธหลักให้เน้นการเล่นร่วมกันมากขึ้น นอกจากนี้ยังเปิด Ranked Play อย่างเป็นทางการภายในซีซันนี้', 'สำรวจเนื้อหา Zero-Day และของรางวัลสุดพิเศษใน Battlefield REDSEC', 'published', 'https://bsmedia.business-standard.com/_media/bs/img/article/2025-10/28/full/1761638363-2733.png', 2),
('คู่มือจัดทีม Ultimate Team ใน EA SPORTS FC 26 สำหรับมือใหม่', 'เราได้สรุปกลยุทธ์การสร้างทีมที่คุ้มค่าที่สุดในสัปดาห์แรก รวมถึงการใช้แต้ม 5,900 FC Points ให้เกิดประโยชน์สูงสุดและวิธีปลดล็อกนักเตะไอคอนแบบไม่ต้องเติมหนัก', 'วางแผนใช้ FC Points อย่างคุ้มค่าตั้งแต่วันแรก', 'published', 'https://retrogems.fr/wp-content/uploads/2025/09/ea-sports-fc-26-ultimate-edition.jpg', 2);
