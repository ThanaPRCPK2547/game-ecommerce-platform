<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'simple_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// ลองเชื่อมต่อฐานข้อมูล (ไม่บังคับ)
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_connected = true;
} catch(PDOException $e) {
    $db_connected = false;
    // สร้างข้อมูลจำลองถ้าไม่มี DB
    $pdo = null;
}

session_start();

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

// ข้อมูลจำลองถ้าไม่มี DB
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
