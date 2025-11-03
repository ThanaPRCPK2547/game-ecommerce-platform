<?php
require_once __DIR__ . '/../config.php';

// CORS (allow during development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

try {
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
    if ($limit < 1 || $limit > 100) { $limit = 20; }

    $stmt = $pdo->prepare(
        'SELECT p.id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                c.id as category_id, c.name as category_name, p.created_at
         FROM products p 
         LEFT JOIN categories c ON p.category_id = c.id 
         ORDER BY p.created_at DESC 
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo json_encode(['ok' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>


