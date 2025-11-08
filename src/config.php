<?php
// Database configuration for Awardspace.net
// เปลี่ยนข้อมูลเหล่านี้ตามที่ Awardspace ให้มา
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'WebGameStore');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: 'root_password_123');

// Site configuration
define('SITE_NAME', 'My PHP Website');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost:8000');

// Error reporting (ปิดใน production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Database connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // ถ้าฐานข้อมูลยังไม่ถูกสร้าง (SQLSTATE[HY000] [1049]) ให้แสดงคำแนะนำอย่างชัดเจน
    if (strpos($e->getMessage(), "1049") !== false || stripos($e->getMessage(), 'Unknown database') !== false) {
        $hint = "\n\nวิธีแก้ไขอย่างรวดเร็ว:\n" .
                "1) สร้างฐานข้อมูล:\n   mysql -u " . DB_USER . " -p -e \"CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;\"\n" .
                "2) นำเข้า schema:\n   mysql -u " . DB_USER . " -p " . DB_NAME . " < " . __DIR__ . "/database.sql\n" .
                "\nหมายเหตุ: ถ้าใช้ MAMP ให้ใช้พอร์ต 8889 และรหัสผ่าน root (ตั้งค่าด้วย DB_PORT=8889)";
        die("Connection failed: " . $e->getMessage() . $hint);
    }
    die("Connection failed: " . $e->getMessage());
}
?>
