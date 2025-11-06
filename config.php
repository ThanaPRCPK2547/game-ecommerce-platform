<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'simple_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}
?>
