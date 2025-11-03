-- Database schema for PHP Website
-- สร้างฐานข้อมูลและตารางสำหรับเว็บไซต์

-- สร้างฐานข้อมูล (ถ้าไม่มี)
CREATE DATABASE IF NOT EXISTS php_website;
USE php_website;

-- ตาราง users สำหรับเก็บข้อมูลผู้ใช้
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ตาราง posts สำหรับเก็บข้อมูลโพสต์/บทความ
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ตาราง categories สำหรับหมวดหมู่
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง products สำหรับสินค้า (ตัวอย่าง)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO categories (name, description) VALUES 
('Electronics', 'อุปกรณ์อิเล็กทรอนิกส์'),
('Clothing', 'เสื้อผ้าและแฟชั่น'),
('Books', 'หนังสือและสื่อการเรียนรู้'),
('Home & Garden', 'ของใช้ในบ้านและสวน');

INSERT INTO products (name, description, price, category_id, stock_quantity) VALUES 
('iPhone 15 Pro', 'สมาร์ทโฟนรุ่นล่าสุดจาก Apple', 39900.00, 1, 10),
('MacBook Air M2', 'แล็ปท็อปสำหรับงานและความบันเทิง', 45900.00, 1, 5),
('Nike Air Max', 'รองเท้ากีฬาสุดคลาสสิก', 4500.00, 2, 20),
('The Great Gatsby', 'นวนิยายคลาสสิกของ F. Scott Fitzgerald', 350.00, 3, 50),
('Garden Tools Set', 'ชุดเครื่องมือทำสวนครบครัน', 1200.00, 4, 15);

-- สร้างผู้ใช้ admin เริ่มต้น (รหัสผ่าน: admin123)
INSERT INTO users (username, email, password, full_name) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- เพิ่มโพสต์ตัวอย่าง
INSERT INTO posts (title, content, author_id, status) VALUES 
('ยินดีต้อนรับสู่เว็บไซต์ของเรา', 'นี่คือโพสต์แรกของเว็บไซต์ เราใช้ PHP และ MySQL ในการพัฒนา', 1, 'published'),
('วิธีการใช้งานระบบ', 'คู่มือการใช้งานระบบสำหรับผู้ใช้ใหม่', 1, 'published');
