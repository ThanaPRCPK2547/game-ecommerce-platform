# โครงสร้างเว็บแบบง่าย สำหรับทีม 2 คน

## 📁 โครงสร้างใหม่ (ลดจาก 7 ไฟล์เหลือ 4 ไฟล์หลัก)

```
simple-ecommerce/
├── index.php          # หน้าหลัก + แสดงสินค้า
├── admin.php          # จัดการทุกอย่าง (สินค้า + ผู้ใช้)
├── config.php         # ตั้งค่าฐานข้อมูล
└── style.css          # CSS ทั้งหมด
```

## 🎯 ฟีเจอร์ที่เหลือ (เฉพาะที่จำเป็น)

### หน้าหลัก (index.php)
- แสดงสินค้าทั้งหมด
- ค้นหาสินค้า
- ดูรายละเอียดสินค้า

### หน้า Admin (admin.php)
- เข้าสู่ระบบ
- เพิ่ม/แก้ไข/ลบ สินค้า
- ดูรายการสั่งซื้อ (ถ้ามี)

## 🗄️ ฐานข้อมูลแบบง่าย (3 ตารางหลัก)

```sql
-- ตาราง products (สินค้า)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง users (ผู้ใช้ - เฉพาะ admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin'
);

-- ตาราง orders (คำสั่งซื้อ - ถ้าต้องการ)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    product_id INT,
    quantity INT DEFAULT 1,
    total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

## 👥 การแบ่งงาน 2 คน

### คน A (Frontend + UX)
- ดูแล `index.php` (หน้าลูกค้า)
- ดูแล `style.css` (การออกแบบ)
- ทดสอบ responsive design

### คน B (Backend + Admin)
- ดูแล `admin.php` (ระบบจัดการ)
- ดูแล `config.php` (ฐานข้อมูล)
- ดูแลความปลอดภัย

## 🚀 ข้อดีของโครงสร้างใหม่

✅ **ไฟล์น้อยลง** - จาก 7 เหลือ 4 ไฟล์
✅ **จัดการง่าย** - แต่ละคนดูแลส่วนของตัวเอง
✅ **Deploy เร็ว** - อัปโหลดไฟล์น้อย
✅ **Debug ง่าย** - โค้ดอยู่ในที่เดียว
✅ **เหมาะ startup** - เริ่มต้นได้เร็ว

## 📝 สิ่งที่ตัดออก

❌ ระบบบทความ (ไม่จำเป็นสำหรับ e-commerce)
❌ ระบบหมวดหมู่ซับซ้อน (ใช้การค้นหาแทน)
❌ ระบบผู้ใช้หลายระดับ (เหลือแค่ admin)
❌ ไฟล์ .htaccess ซับซ้อน
❌ ระบบสถิติที่ซับซ้อน
