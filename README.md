# ร้านค้าออนไลน์แบบง่าย

เว็บไซต์ e-commerce ที่พัฒนาด้วย PHP และ MySQL เหมาะสำหรับทีม 2 คน

## 📁 โครงสร้างไฟล์

```
ecommerce-app/
├── index.php          # หน้าลูกค้า (แสดงสินค้า + ค้นหา)
├── admin.php          # หน้า Admin (จัดการสินค้า)
├── config.php         # การตั้งค่าฐานข้อมูล
├── style.css          # ไฟล์ CSS
├── database.sql       # ไฟล์ SQL สำหรับสร้างฐานข้อมูล
├── simple-structure.md # คู่มือโครงสร้าง
└── README.md          # คู่มือการใช้งาน
```

## 🚀 การติดตั้ง

1. **สร้างฐานข้อมูล**
   ```sql
   CREATE DATABASE simple_shop;
   ```

2. **Import ฐานข้อมูล**
   ```bash
   mysql -u root -p simple_shop < database.sql
   ```

3. **ตั้งค่าการเชื่อมต่อ** (แก้ไขใน `config.php`)
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'simple_shop');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

## 👥 การใช้งาน

### สำหรับลูกค้า
- เข้า `index.php` เพื่อดูสินค้า
- ใช้ช่องค้นหาเพื่อหาสินค้า

### สำหรับ Admin
- เข้า `admin.php`
- Login: `admin` / `admin123`
- เพิ่ม/ลบ สินค้า

## 👨‍💻 การแบ่งงาน 2 คน

**คน A (Frontend):**
- `index.php` + `style.css`
- UX/UI Design

**คน B (Backend):**
- `admin.php` + `config.php`
- Database + Security

## 🛠️ เทคโนโลยี

- PHP 7.4+
- MySQL 5.7+
- HTML5 & CSS3
- JavaScript (ES6+)
