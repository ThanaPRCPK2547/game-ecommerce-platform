<?php
    /*
    // คอมเมนต์โค้ดส่วนทดสอบ SQLite ออกไปก่อน เพราะไม่เกี่ยวข้องกับ Error หลัก
    $db = new SQLite3('/Applications/MAMP/db/sqlite/mydatabase.db');
    $db->exec("CREATE TABLE IF NOT EXISTS items(id INTEGER PRIMARY KEY, name TEXT)"); // เพิ่ม IF NOT EXISTS เพื่อแก้ Warning
    $db->exec("INSERT INTO items(name) VALUES('Name 1')");
    $db->exec("INSERT INTO items(name) VALUES('Name 2')");

    $last_row_id = $db->lastInsertRowID();

    echo 'The last inserted row ID is '.$last_row_id.'.';

    $result = $db->query('SELECT * FROM items');

    while ($row = $result->fetchArray()) {
        echo '<br>';
        echo 'id: '.$row['id'].' / name: '.$row['name'];
    }

    $db->exec('DELETE FROM items');

    $changes = $db->changes();

    echo '<br>';
    echo 'The DELETE statement removed '.$changes.' rows.';
    */


// --- ส่วนนี้คือโค้ดที่ index.php ต้องการ ---

// **สำคัญ:** เพิ่มการกำหนดค่าฐานข้อมูล MAMP ของคุณที่นี่
// (ค่าเริ่มต้นของ MAMP มักจะเป็น user 'root' และ pass 'root')
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name'); // แก้ไขเป็นชื่อฐานข้อมูลของคุณ
define('DB_USER', 'root');
define('DB_PASS', 'root');


// ลองเชื่อมต่อฐานข้อมูล (เปิดใช้งานส่วนนี้)
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_connected = true;
} catch(PDOException $e) {
    $db_connected = false;
    // สร้างข้อมูลจำลองถ้าไม่มี DB
    $pdo = null;
}

// เปิดใช้งาน session
session_start();

// เปิดใช้งานฟังก์ชัน isAdmin
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

// ข้อมูลจำลองถ้าไม่มี DB (เปิดใช้งานฟังก์ชันนี้)
function getMockProducts() {
    return [
        [
            'id' => 1,
            'name' => 'Cyberpunk 2077',
            'price' => '฿1,999',
            'description' => 'เกม RPG โลกอนาคตที่เต็มไปด้วยเทคโนโลยี',
            'image' => 'https://cdn.akamai.steamstatic.com/steam/apps/1091500/header.jpg',
            'category' => 'steam',
            'genres' => 'RPG, Action',
            'rating' => 4.2
        ],
        [
            'id' => 2,
            'name' => 'Valorant',
            'price' => 'Free',
            'description' => 'เกมยิง FPS แบบ 5v5 จาก Riot Games',
            'image' => 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt092d4bcdd6b2b7b7/5eb7cdc1b1f2e27c950d3d0d/Agent_17_KeyArt_1920x1080.jpg',
            'category' => 'epic',
            'genres' => 'FPS, Tactical',
            'rating' => 4.5
        ],
        [
            'id' => 3,
            'name' => 'Minecraft',
            'price' => '฿890',
            'description' => 'เกมสร้างโลกและเอาชีวิตรอดที่ได้รับความนิยม',
            'image' => 'https://www.minecraft.net/content/dam/games/minecraft/key-art/Games_Subnav_Minecraft-300x465.jpg',
            'category' => 'steam',
            'genres' => 'Sandbox, Survival',
            'rating' => 4.8
        ]
    ];
}
?>