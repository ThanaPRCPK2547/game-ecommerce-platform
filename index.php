<?php
require_once 'config.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM products WHERE name LIKE :search ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านค้าออนไลน์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🛍️ ร้านค้าออนไลน์</h1>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">ค้นหา</button>
        </form>
        <a href="admin.php" class="admin-link">เข้าสู่ระบบ Admin</a>
    </header>

    <main>
        <div class="products-grid">
            <?php if (empty($products)): ?>
                <p class="no-products">ไม่พบสินค้า</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <?php if ($product['image']): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price">฿<?= number_format($product['price'], 2) ?></p>
                        <p class="description"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                        <button class="buy-btn" onclick="buyProduct(<?= $product['id'] ?>)">สั่งซื้อ</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function buyProduct(id) {
            alert('ติดต่อสั่งซื้อสินค้า ID: ' + id);
        }
    </script>
</body>
</html>
