<?php
require_once __DIR__ . '/../config.php';

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
        "SELECT p.id, p.title, p.content, p.status, p.created_at, 
                u.id as author_id, u.full_name as author_name
         FROM posts p 
         LEFT JOIN users u ON p.author_id = u.id 
         WHERE p.status = 'published'
         ORDER BY p.created_at DESC 
         LIMIT :limit"
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


