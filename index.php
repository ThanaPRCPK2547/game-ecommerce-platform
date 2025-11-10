<?php
require_once 'config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// ใช้ข้อมูลจำลองถ้าไม่มี DB
if ($pdo) {
    $sql = "SELECT * FROM products WHERE name LIKE :search";
    $params = ['search' => "%$search%"];

    if ($category) {
        $sql .= " AND category = :category";
        $params['category'] = $category;
    }

    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} else {
    // ใช้ข้อมูลจำลอง
    $products = getMockProducts();
    
    // กรองตามการค้นหา
    if ($search) {
        $products = array_filter($products, function($product) use ($search) {
            return stripos($product['name'], $search) !== false;
        });
    }
    
    // กรองตามหมวดหมู่
    if ($category) {
        $products = array_filter($products, function($product) use ($category) {
            return $product['category'] === $category;
        });
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎮 Game Store - ร้านเกมออนไลน์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🎮 Game Store</h1>
        <div class="header-controls">
            <form method="GET" class="search-form">
                <select name="category">
                    <option value="">ทุกแพลตฟอร์ม</option>
                    <option value="steam" <?= $category === 'steam' ? 'selected' : '' ?>>Steam</option>
                    <option value="epic" <?= $category === 'epic' ? 'selected' : '' ?>>Epic Games</option>
                    <option value="mobile" <?= $category === 'mobile' ? 'selected' : '' ?>>Mobile</option>
                </select>
                <input type="text" name="search" placeholder="ค้นหาเกม..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">🔍</button>
            </form>
            <div class="api-buttons">
                <button onclick="loadSteamGames()" class="api-btn steam">Steam API</button>
                <button onclick="loadEpicGames()" class="api-btn epic">Epic Free</button>
            </div>
            <a href="admin.php" class="admin-link">Admin</a>
        </div>
    </header>

    <?php if (!$pdo): ?>
        <div class="db-warning">
            ⚠️ ไม่สามารถเชื่อมต่อฐานข้อมูลได้ - กำลังใช้ข้อมูลจำลอง
        </div>
    <?php endif; ?>

    <main>
        <div id="api-games" class="api-section" style="display: none;">
            <h2 id="api-title">กำลังโหลด...</h2>
            <div id="api-content" class="products-grid"></div>
        </div>

        <div class="products-section">
            <h2>เกมในร้าน (<?= count($products) ?> เกม)</h2>
            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <p class="no-products">ไม่พบเกม</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="platform-badge <?= $product['category'] ?>"><?= strtoupper($product['category']) ?></div>
                            <?php if ($product['image']): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php endif; ?>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="price"><?= htmlspecialchars($product['price']) ?></p>
                                <?php if ($product['genres']): ?>
                                    <p class="genres"><?= htmlspecialchars($product['genres']) ?></p>
                                <?php endif; ?>
                                <?php if ($product['rating']): ?>
                                    <div class="rating">⭐ <?= $product['rating'] ?>/5</div>
                                <?php endif; ?>
                                <p class="description"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                <button class="buy-btn" onclick="buyGame(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                                    <?= strpos($product['price'], 'Free') !== false ? 'ดาวน์โหลด' : 'ซื้อเกม' ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        async function loadSteamGames() {
            document.getElementById('api-games').style.display = 'block';
            document.getElementById('api-title').textContent = 'เกมยอดนิยมจาก Steam';
            document.getElementById('api-content').innerHTML = '<p>กำลังโหลดจาก Steam API...</p>';
            
            try {
                const response = await fetch('api.php?action=steam');
                const games = await response.json();
                displayApiGames(games);
            } catch (error) {
                document.getElementById('api-content').innerHTML = '<p>ไม่สามารถโหลดข้อมูลได้ (ต้องมี internet)</p>';
            }
        }

        async function loadEpicGames() {
            document.getElementById('api-games').style.display = 'block';
            document.getElementById('api-title').textContent = 'เกมฟรีจาก Epic Games';
            document.getElementById('api-content').innerHTML = '<p>กำลังโหลดจาก Epic API...</p>';
            
            try {
                const response = await fetch('api.php?action=epic');
                const games = await response.json();
                displayApiGames(games);
            } catch (error) {
                document.getElementById('api-content').innerHTML = '<p>ไม่สามารถโหลดข้อมูลได้ (ต้องมี internet)</p>';
            }
        }

        function displayApiGames(games) {
            const content = document.getElementById('api-content');
            if (games.length === 0) {
                content.innerHTML = '<p>ไม่พบเกม</p>';
                return;
            }

            content.innerHTML = games.map(game => `
                <div class="product-card api-game">
                    <img src="${game.image}" alt="${game.name}" onerror="this.src='https://via.placeholder.com/300x200/333/fff?text=Game'">
                    <div class="product-info">
                        <h3>${game.name}</h3>
                        <p class="price">${game.price}</p>
                        ${game.genres ? `<p class="genres">${game.genres}</p>` : ''}
                        <p class="description">${game.description}</p>
                        <button class="buy-btn" onclick="alert('เกมจาก API: ${game.name}')">ดูรายละเอียด</button>
                    </div>
                </div>
            `).join('');
        }

        function buyGame(id, name) {
            alert(`สั่งซื้อเกม: ${name} (ID: ${id})\n\nติดต่อ Admin เพื่อรับโค้ดเกม`);
        }
    </script>
</body>
</html>
