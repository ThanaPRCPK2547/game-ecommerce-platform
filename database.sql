-- ฐานข้อมูลแบบง่าย สำหรับทีม 2 คน
-- ใช้เพียง 3 ตารางหลัก

CREATE DATABASE IF NOT EXISTS simple_shop;
USE simple_shop;

-- ตาราง products (สินค้า)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง users (ผู้ใช้ - เฉพาะ admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง orders (คำสั่งซื้อ - ถ้าต้องการ)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    product_id INT,
    quantity INT DEFAULT 1,
    total DECIMAL(10,2),
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: admin123

INSERT INTO products (name, price, description, image) VALUES 
('เสื้อยืดสีขาว', 299.00, 'เสื้อยืดคุณภาพดี ผ้าฝ้าย 100%', 'https://via.placeholder.com/300x300/ffffff/000000?text=White+Shirt'),
('กางเกงยีนส์', 899.00, 'กางเกงยีนส์ทรงสวย ใส่สบาย', 'https://via.placeholder.com/300x300/4169e1/ffffff?text=Jeans'),
('รองเท้าผ้าใบ', 1299.00, 'รองเท้าผ้าใบสำหรับวิ่งและออกกำลังกาย', 'https://via.placeholder.com/300x300/ff6b6b/ffffff?text=Sneakers'),
('กระเป๋าเป้', 599.00, 'กระเป๋าเป้สำหรับเดินทาง ใส่ของได้เยอะ', 'https://via.placeholder.com/300x300/4ecdc4/ffffff?text=Backpack'),
('หูฟังบลูทูธ', 1599.00, 'หูฟังไร้สาย เสียงใส คุณภาพสูง', 'https://via.placeholder.com/300x300/45b7d1/ffffff?text=Headphones');
