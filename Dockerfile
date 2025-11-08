# 1. บอกว่าให้เริ่มจากอิมเมจ PHP ที่เราใช้อยู่
FROM php:8.2-apache

# 2. สั่งให้ติดตั้ง "ไดรเวอร์" สำหรับ MySQL (ทั้ง pdo_mysql และ mysqli)
RUN docker-php-ext-install pdo_mysql mysqli