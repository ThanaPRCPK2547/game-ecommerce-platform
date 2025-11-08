<?php
require_once __DIR__ . '/../config.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query('SELECT 1');
    $dbOk = (bool) $stmt->fetchColumn();
    echo json_encode([
        'ok' => true,
        'db' => $dbOk,
        'host' => DB_HOST,
        'port' => defined('DB_PORT') ? DB_PORT : '3306',
        'name' => DB_NAME,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>


